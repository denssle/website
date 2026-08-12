<?php

/**
 * Besucherzähler, wie ihn Seiten früher hatten – nur ohne Datenkrake.
 *
 * Gezählt wird ein Besuch pro Tag und Gerät. Wiedererkannt wird ein Gerät über
 * einen Hash aus IP-Adresse und Browserkennung; der Schlüssel dafür (das Salz)
 * wird jeden Tag neu gewürfelt und der Datenbestand des Vortags dabei verworfen.
 * Damit lässt sich schon am Folgetag nicht mehr feststellen, welcher Hash zu
 * welcher IP-Adresse gehörte – gespeichert bleibt nur eine Zahl.
 *
 * Die Datei mit dem Stand liegt bewusst NICHT im ausgelieferten Verzeichnis,
 * sondern eine Ebene darüber. Zwei Gründe: der Webserver liefert sie so nicht
 * aus, und das Deployment (rsync mit --delete auf ~/html) räumt sie nicht weg.
 */

declare(strict_types=1);

const ZAEHLER_DATEI = __DIR__ . '/../zaehler-daten/stand.json';

/** Mehr Kennungen als das hebt ein einzelner Tag nicht – Schutz vor Ausufern. */
const ZAEHLER_MAX_KENNUNGEN = 50000;

/**
 * Zählt den aktuellen Aufruf und gibt den Gesamtstand zurück.
 *
 * Gibt null zurück, wenn der Stand nicht gelesen oder geschrieben werden kann.
 * Der Zähler ist Beiwerk: schlägt er fehl, fehlt die Zeile im Fuß – die Seite
 * darf daran nicht scheitern.
 */
function zaehler_stand(): ?int
{
	$verzeichnis = dirname(ZAEHLER_DATEI);
	if (!is_dir($verzeichnis) && !@mkdir($verzeichnis, 0755, true) && !is_dir($verzeichnis)) {
		return null;
	}

	// 'c+' legt die Datei an, falls sie fehlt, kürzt eine vorhandene aber nicht.
	$griff = @fopen(ZAEHLER_DATEI, 'c+');
	if ($griff === false) {
		return null;
	}

	try {
		// Ohne Sperre verlieren zwei gleichzeitige Aufrufe einen Zählschritt.
		if (!flock($griff, LOCK_EX)) {
			return null;
		}

		$inhalt = stream_get_contents($griff);
		$daten = is_string($inhalt) ? json_decode($inhalt, true) : null;
		if (!is_array($daten)) {
			$daten = [];
		}

		$gesamt = isset($daten['gesamt']) ? (int) $daten['gesamt'] : 0;
		$tag = isset($daten['tag']) ? (string) $daten['tag'] : '';
		$salz = isset($daten['salz']) ? (string) $daten['salz'] : '';
		$kennungen = isset($daten['kennungen']) && is_array($daten['kennungen'])
			? $daten['kennungen']
			: [];

		$heute = date('Y-m-d');
		if ($tag !== $heute || $salz === '') {
			// Tageswechsel: neues Salz, alte Kennungen weg. Ab hier sind die
			// Hashes von gestern niemandem mehr zuzuordnen.
			$tag = $heute;
			$salz = bin2hex(random_bytes(32));
			$kennungen = [];
		}

		if (!zaehler_ist_bot() && count($kennungen) < ZAEHLER_MAX_KENNUNGEN) {
			$kennung = hash_hmac('sha256', zaehler_ip() . '|' . zaehler_browser(), $salz);
			// Nur zählen, was sich auch merken lässt – sonst zählte oberhalb der
			// Grenze jeder einzelne Aufruf erneut mit.
			if (!isset($kennungen[$kennung])) {
				$kennungen[$kennung] = true;
				$gesamt++;
			}
		}

		$neu = json_encode([
			'gesamt' => $gesamt,
			'tag' => $tag,
			'salz' => $salz,
			'kennungen' => $kennungen,
		]);
		if ($neu !== false) {
			rewind($griff);
			ftruncate($griff, 0);
			fwrite($griff, $neu);
			fflush($griff);
		}

		return $gesamt;
	} catch (Throwable $fehler) {
		return null;
	} finally {
		flock($griff, LOCK_UN);
		fclose($griff);
	}
}

/**
 * Der Zählerstand als einzelne Ziffernkästchen, mit führenden Nullen wie am
 * Kilometerzähler. $stellen gibt die Mindestbreite vor.
 */
function zaehler_ziffern(int $stand, int $stellen = 6): string
{
	$ziffern = str_pad((string) $stand, $stellen, '0', STR_PAD_LEFT);
	$html = '';
	foreach (str_split($ziffern) as $ziffer) {
		$html .= '<span class="zaehler-ziffer">' . $ziffer . '</span>';
	}

	return $html;
}

/**
 * Die IP-Adresse des Besuchers.
 *
 * Auf dem Uberspace steht nginx vor Apache. Kommt die Anfrage von dort, ist
 * REMOTE_ADDR die des Proxys – dann zählt der erste Eintrag aus
 * X-Forwarded-For. Von außen ist der Kopf nicht zu trauen, deshalb wird er nur
 * herangezogen, wenn die direkte Gegenstelle tatsächlich lokal ist.
 */
function zaehler_ip(): string
{
	$adresse = (string) ($_SERVER['REMOTE_ADDR'] ?? '');

	$ist_lokal = $adresse === '' || filter_var(
		$adresse,
		FILTER_VALIDATE_IP,
		FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
	) === false;

	if ($ist_lokal && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$kette = explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR']);
		$erste = trim($kette[0]);
		if (filter_var($erste, FILTER_VALIDATE_IP) !== false) {
			return $erste;
		}
	}

	return $adresse;
}

function zaehler_browser(): string
{
	return (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
}

/**
 * Grobe Bot-Erkennung. Sie fängt nicht jeden, aber die üblichen Verdächtigen –
 * ohne sie steht im Zähler vor allem, wie oft Suchmaschinen vorbeischauen.
 */
function zaehler_ist_bot(): bool
{
	$browser = zaehler_browser();
	if ($browser === '') {
		return true;
	}

	$muster = '/bot|crawl|spider|slurp|search|fetch|monitor|preview|scrape|'
		. 'curl|wget|python|java|go-http|headless|lighthouse|pingdom|uptime/i';

	return preg_match($muster, $browser) === 1;
}
