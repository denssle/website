<?php
// Der einzige dynamische Teil dieser Seite. Beide Sprachfassungen zählen auf
// denselben Stand – es ist eine Seite, nicht zwei.
require dirname(__DIR__) . '/zaehler.php';
$besuche = zaehler_stand();
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Dominik Hellweg – Projects</title>
		<meta
			name="description"
			content="An overview of my projects: a planner for private events, a Discord bot and a browser game."
		/>
		<meta name="color-scheme" content="dark" />
		<meta name="theme-color" content="#181818" />
		<link rel="stylesheet" href="../style.css" />
		<!-- hreflang verlangt absolute URLs. x-default zeigt auf die deutsche
		     Fassung: sie ist der Standard, /en/ die Alternative. -->
		<link rel="alternate" hreflang="de" href="https://enzlor.uber.space/" />
		<link rel="alternate" hreflang="en" href="https://enzlor.uber.space/en/" />
		<link
			rel="alternate"
			hreflang="x-default"
			href="https://enzlor.uber.space/"
		/>
		<!-- Vorschaukarte in Messengern und sozialen Netzen. Das Bild ist 1200×630
		     groß – das Format, das LinkedIn, Facebook und Co. als große Karte
		     ausspielen; kleinere Bilder landen als Briefmarke neben dem Text.
		     Erzeugt aus den Farben der Seite, mit englischer Beschriftung. Die
		     absoluten URLs müssen wie die hreflang-Angaben mitgezogen werden,
		     wenn die Seite auf eine eigene Domain umzieht. -->
		<meta property="og:type" content="website" />
		<meta property="og:site_name" content="Dominik Hellweg" />
		<meta property="og:title" content="Dominik Hellweg – Projects" />
		<meta
			property="og:description"
			content="An overview of my projects: a planner for private events, a Discord bot and a browser game."
		/>
		<meta property="og:url" content="https://enzlor.uber.space/en/" />
		<meta
			property="og:image"
			content="https://enzlor.uber.space/en/vorschaubild.png"
		/>
		<meta property="og:image:type" content="image/png" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta
			property="og:image:alt"
			content="Dominik Hellweg – Projects. A web app, a Discord bot and a browser game."
		/>
		<meta property="og:locale" content="en_US" />
		<meta property="og:locale:alternate" content="de_DE" />
		<meta name="twitter:card" content="summary_large_image" />
		<link rel="icon" type="image/png" href="../favicon.png" />
	</head>
	<body>
		<!-- Icon-Vorrat: einmal definiert, per <use> an jeder Stelle eingesetzt.
		     Quellen: Primer Octicons (mark-github) und Simple Icons (der Rest).
		     Inline statt als Datei, damit die Seite ohne zusätzlichen Request
		     auskommt. Die viewBox steht am Symbol, weil die Vorlagen sich darin
		     unterscheiden (16 gegen 24). -->
		<svg class="icon-vorrat" aria-hidden="true">
			<symbol id="icon-github" viewBox="0 0 16 16">
				<path
					d="M8 0c4.42 0 8 3.58 8 8a8.013 8.013 0 0 1-5.45 7.59c-.4.08-.55-.17-.55-.38 0-.27.01-1.13.01-2.2 0-.75-.25-1.23-.54-1.48 1.78-.2 3.65-.88 3.65-3.95 0-.88-.31-1.59-.82-2.15.08-.2.36-1.02-.08-2.12 0 0-.67-.22-2.2.82-.64-.18-1.32-.27-2-.27-.68 0-1.36.09-2 .27-1.53-1.03-2.2-.82-2.2-.82-.44 1.1-.16 1.92-.08 2.12-.51.56-.82 1.28-.82 2.15 0 3.06 1.86 3.75 3.64 3.95-.23.2-.44.55-.51 1.07-.46.21-1.61.55-2.33-.66-.15-.24-.6-.83-1.23-.82-.67.01-.27.38.01.53.34.19.73.9.82 1.13.16.45.68 1.31 2.69.94 0 .67.01 1.3.01 1.49 0 .21-.15.45-.55.38A7.995 7.995 0 0 1 0 8c0-4.42 3.58-8 8-8Z"
				/>
			</symbol>
			<symbol id="icon-steam" viewBox="0 0 24 24">
				<path
					d="M11.979 0C5.678 0 .511 4.86.022 11.037l6.432 2.658c.545-.371 1.203-.59 1.912-.59.063 0 .125.004.188.006l2.861-4.142V8.91c0-2.495 2.028-4.524 4.524-4.524 2.494 0 4.524 2.031 4.524 4.527s-2.03 4.525-4.524 4.525h-.105l-4.076 2.911c0 .052.004.105.004.159 0 1.875-1.515 3.396-3.39 3.396-1.635 0-3.016-1.173-3.331-2.727L.436 15.27C1.862 20.307 6.486 24 11.979 24c6.627 0 11.999-5.373 11.999-12S18.605 0 11.979 0zM7.54 18.21l-1.473-.61c.262.543.714.999 1.314 1.25 1.297.539 2.793-.076 3.332-1.375.263-.63.264-1.319.005-1.949s-.75-1.121-1.377-1.383c-.624-.26-1.29-.249-1.878-.03l1.523.63c.956.4 1.409 1.5 1.009 2.455-.397.957-1.497 1.41-2.454 1.012H7.54zm11.415-9.303c0-1.662-1.353-3.015-3.015-3.015-1.665 0-3.015 1.353-3.015 3.015 0 1.665 1.35 3.015 3.015 3.015 1.663 0 3.015-1.35 3.015-3.015zm-5.273-.005c0-1.252 1.013-2.266 2.265-2.266 1.249 0 2.266 1.014 2.266 2.266 0 1.251-1.017 2.265-2.266 2.265-1.253 0-2.265-1.014-2.265-2.265z"
				/>
			</symbol>
			<symbol id="icon-spotify" viewBox="0 0 24 24">
				<path
					d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"
				/>
			</symbol>
			<symbol id="icon-lastfm" viewBox="0 0 24 24">
				<path
					d="M10.584 17.21l-.88-2.392s-1.43 1.594-3.573 1.594c-1.897 0-3.244-1.649-3.244-4.288 0-3.382 1.704-4.591 3.381-4.591 2.42 0 3.189 1.567 3.849 3.574l.88 2.749c.88 2.666 2.529 4.81 7.285 4.81 3.409 0 5.718-1.044 5.718-3.793 0-2.227-1.265-3.381-3.63-3.931l-1.758-.385c-1.21-.275-1.567-.77-1.567-1.595 0-.934.742-1.484 1.952-1.484 1.32 0 2.034.495 2.144 1.677l2.749-.33c-.22-2.474-1.924-3.492-4.729-3.492-2.474 0-4.893.935-4.893 3.932 0 1.87.907 3.051 3.189 3.601l1.87.44c1.402.33 1.869.907 1.869 1.704 0 1.017-.99 1.43-2.86 1.43-2.776 0-3.93-1.457-4.59-3.464l-.907-2.75c-1.155-3.573-2.997-4.893-6.653-4.893C2.144 5.333 0 7.89 0 12.233c0 4.18 2.144 6.434 5.993 6.434 3.106 0 4.591-1.457 4.591-1.457z"
				/>
			</symbol>
			<symbol id="icon-instagram" viewBox="0 0 24 24">
				<path
					d="M7.0301.084c-1.2768.0602-2.1487.264-2.911.5634-.7888.3075-1.4575.72-2.1228 1.3877-.6652.6677-1.075 1.3368-1.3802 2.127-.2954.7638-.4956 1.6365-.552 2.914-.0564 1.2775-.0689 1.6882-.0626 4.947.0062 3.2586.0206 3.6671.0825 4.9473.061 1.2765.264 2.1482.5635 2.9107.308.7889.72 1.4573 1.388 2.1228.6679.6655 1.3365 1.0743 2.1285 1.38.7632.295 1.6361.4961 2.9134.552 1.2773.056 1.6884.069 4.9462.0627 3.2578-.0062 3.668-.0207 4.9478-.0814 1.28-.0607 2.147-.2652 2.9098-.5633.7889-.3086 1.4578-.72 2.1228-1.3881.665-.6682 1.0745-1.3378 1.3795-2.1284.2957-.7632.4966-1.636.552-2.9124.056-1.2809.0692-1.6898.063-4.948-.0063-3.2583-.021-3.6668-.0817-4.9465-.0607-1.2797-.264-2.1487-.5633-2.9117-.3084-.7889-.72-1.4568-1.3876-2.1228C21.2982 1.33 20.628.9208 19.8378.6165 19.074.321 18.2017.1197 16.9244.0645 15.6471.0093 15.236-.005 11.977.0014 8.718.0076 8.31.0215 7.0301.0839m.1402 21.6932c-1.17-.0509-1.8053-.2453-2.2287-.408-.5606-.216-.96-.4771-1.3819-.895-.422-.4178-.6811-.8186-.9-1.378-.1644-.4234-.3624-1.058-.4171-2.228-.0595-1.2645-.072-1.6442-.079-4.848-.007-3.2037.0053-3.583.0607-4.848.05-1.169.2456-1.805.408-2.2282.216-.5613.4762-.96.895-1.3816.4188-.4217.8184-.6814 1.3783-.9003.423-.1651 1.0575-.3614 2.227-.4171 1.2655-.06 1.6447-.072 4.848-.079 3.2033-.007 3.5835.005 4.8495.0608 1.169.0508 1.8053.2445 2.228.408.5608.216.96.4754 1.3816.895.4217.4194.6816.8176.9005 1.3787.1653.4217.3617 1.056.4169 2.2263.0602 1.2655.0739 1.645.0796 4.848.0058 3.203-.0055 3.5834-.061 4.848-.051 1.17-.245 1.8055-.408 2.2294-.216.5604-.4763.96-.8954 1.3814-.419.4215-.8181.6811-1.3783.9-.4224.1649-1.0577.3617-2.2262.4174-1.2656.0595-1.6448.072-4.8493.079-3.2045.007-3.5825-.006-4.848-.0608M16.953 5.5864A1.44 1.44 0 1 0 18.39 4.144a1.44 1.44 0 0 0-1.437 1.4424M5.8385 12.012c.0067 3.4032 2.7706 6.1557 6.173 6.1493 3.4026-.0065 6.157-2.7701 6.1506-6.1733-.0065-3.4032-2.771-6.1565-6.174-6.1498-3.403.0067-6.156 2.771-6.1496 6.1738M8 12.0077a4 4 0 1 1 4.008 3.9921A3.9996 3.9996 0 0 1 8 12.0077"
				/>
			</symbol>
			<symbol id="icon-linkedin" viewBox="0 0 24 24">
				<path
					d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
				/>
			</symbol>
			<symbol id="icon-mail" viewBox="0 0 16 16">
				<path
					d="M1.75 2h12.5c.966 0 1.75.784 1.75 1.75v8.5A1.75 1.75 0 0 1 14.25 14H1.75A1.75 1.75 0 0 1 0 12.25v-8.5C0 2.784.784 2 1.75 2ZM1.5 12.251c0 .138.112.25.25.25h12.5a.25.25 0 0 0 .25-.25V5.809L8.38 9.397a.75.75 0 0 1-.76 0L1.5 5.809v6.442Zm13-8.181v-.32a.25.25 0 0 0-.25-.25H1.75a.25.25 0 0 0-.25.25v.32L8 7.88Z"
				/>
			</symbol>
		</svg>

		<header class="kopf">
			<div class="kopf-zeile">
				<p class="augenbraue">Dominik Hellweg</p>
				<!-- Relative Ziele, damit der Umschalter auch beim lokalen Öffnen
				     per file:// funktioniert. -->
				<nav class="sprachwahl" aria-label="Language">
					<a href="../" hreflang="de" lang="de">DE</a>
					<span class="sprachwahl-aktiv" aria-current="true">EN</span>
				</nav>
			</div>
			<h1>Projects</h1>
			<p class="vorspann">
				What runs here – a web application for planning events, a Discord bot and
				a browser game in the making. All open source.
			</p>
		</header>

		<main>
			<ul class="projekte">
				<li class="projekt">
					<div class="projekt-kopf">
						<h2>Festival</h2>
						<span class="status status--live">Live</span>
					</div>
					<p>
						A web application for planning private events: schedule dates,
						invite guests, keep track of who has accepted and who has declined.
						Plus profiles, friendships and groups.
					</p>
					<p class="technik">SvelteKit · TypeScript · MariaDB</p>
					<p class="verweise">
						<!-- Mit Schrägstrich: /festival antwortet sonst mit 308 auf /festival/ -->
						<a class="verweis verweis--haupt" href="/festival/">Open the app</a>
						<a class="verweis" href="https://github.com/denssle/festival">
							<svg class="icon" aria-hidden="true" focusable="false">
								<use href="#icon-github" />
							</svg>
							Source
						</a>
					</p>
				</li>

				<li class="projekt">
					<div class="projekt-kopf">
						<h2>Mechanischer Grüner Drache</h2>
						<span class="status">Runs on Discord</span>
					</div>
					<p>
						A Discord bot for the server of
						<a href="https://www.lotgd.de/">LotgD</a>. Ping-pong duels with a
						monthly leaderboard, Twitch live notifications, shared distance
						tracking for sports, a birthday calendar and a connection to the
						game world.
					</p>
					<p class="technik">TypeScript · discord.js · Redis</p>
					<p class="verweise">
						<!-- Admin-Bereich hinter Discord-Login. rel="nofollow", weil die Seite
						     selbst X-Robots-Tag: noindex setzt – das sollte hier nicht
						     unterlaufen werden. Kein Hauptverweis: Der Bot selbst läuft auf
						     Discord, hier kommen nur Admins weiter. -->
						<a class="verweis" href="/config" rel="nofollow"
							>Configuration <span class="hinweis">admins only</span></a
						>
						<a
							class="verweis"
							href="https://github.com/denssle/mechanischer-gruener-drache"
						>
							<svg class="icon" aria-hidden="true" focusable="false">
								<use href="#icon-github" />
							</svg>
							Source &amp; command reference
						</a>
					</p>
				</li>

				<li class="projekt">
					<div class="projekt-kopf">
						<h2>Houses of the Green Dragon</h2>
						<span class="status">In development</span>
					</div>
					<p>
						A browser-based medieval game where you do not play a life but a
						house: your character is mortal, and once they die, one of their
						children takes over. Leave no heirs and the dynasty ends.
					</p>
					<p class="technik">SvelteKit · TypeScript · MariaDB</p>
					<p class="verweise">
						<!-- Mit Schrägstrich, wie beim Festival: /houses antwortet sonst mit
						     308 auf /houses/ -->
						<a class="verweis verweis--haupt" href="/houses/">Open the game</a>
						<a
							class="verweis"
							href="https://github.com/denssle/houses-of-the-green-dragon"
						>
							<svg class="icon" aria-hidden="true" focusable="false">
								<use href="#icon-github" />
							</svg>
							Source &amp; concept
						</a>
					</p>
				</li>
			</ul>
		</main>

		<footer class="fuss">
			<!-- Zwei Zeilen statt vier gestapelter Blöcke: oben, wie man mich
			     erreicht, unten das Rechtliche. Jeweils links die Hauptsache und
			     rechts das Beiwerk – dasselbe Muster wie im Kopf, wo Name und
			     Sprachwahl auseinanderrücken. -->
			<div class="fuss-zeile">
				<!-- Die Adresse steht ausgeschrieben da, nicht nur hinter dem Icon: wer
				     schreiben will, soll sie kopieren können, ohne den Verweis zu
				     öffnen. -->
				<p class="fuss-kontakt">
					<a class="fuss-verweis" href="mailto:dominik.hellweg@protonmail.com">
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-mail" />
						</svg>
						dominik.hellweg@protonmail.com
					</a>
				</p>
				<!-- Nur Icons, keine Adressen: der Name des Ziels steckt im title für
				     die Maus und im vorgelesenen Text für alle anderen, damit kein
				     Verweis namenlos bleibt. rel="me" weist die Profile als dieselbe
				     Person aus. -->
				<nav class="fuss-profile" aria-label="Profiles elsewhere">
					<a
						class="fuss-verweis fuss-verweis--nur-icon"
						href="https://github.com/denssle"
						rel="me"
						title="GitHub"
					>
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-github" />
						</svg>
						<span class="nur-vorlesen">GitHub</span>
					</a>
					<a
						class="fuss-verweis fuss-verweis--nur-icon"
						href="https://steamcommunity.com/id/enzlor/"
						rel="me"
						title="Steam"
					>
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-steam" />
						</svg>
						<span class="nur-vorlesen">Steam</span>
					</a>
					<a
						class="fuss-verweis fuss-verweis--nur-icon"
						href="https://open.spotify.com/user/1127376258"
						rel="me"
						title="Spotify"
					>
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-spotify" />
						</svg>
						<span class="nur-vorlesen">Spotify</span>
					</a>
					<a
						class="fuss-verweis fuss-verweis--nur-icon"
						href="https://www.last.fm/user/SgtSnake"
						rel="me"
						title="Last.fm"
					>
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-lastfm" />
						</svg>
						<span class="nur-vorlesen">Last.fm</span>
					</a>
					<a
						class="fuss-verweis fuss-verweis--nur-icon"
						href="https://www.instagram.com/_enzlor/"
						rel="me"
						title="Instagram"
					>
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-instagram" />
						</svg>
						<span class="nur-vorlesen">Instagram</span>
					</a>
					<a
						class="fuss-verweis fuss-verweis--nur-icon"
						href="https://www.linkedin.com/in/dominik-hellweg"
						rel="me"
						title="LinkedIn"
					>
						<svg class="icon" aria-hidden="true" focusable="false">
							<use href="#icon-linkedin" />
						</svg>
						<span class="nur-vorlesen">LinkedIn</span>
					</a>
				</nav>
			</div>

			<!-- Ehrlicher Hinweis statt einer Enttäuschung hinter dem ersten Klick:
			     die verlinkten Projekte selbst sind deutschsprachig. -->
			<p class="fuss-hinweis">
				Note: the linked projects themselves have German-language interfaces.
			</p>

			<div class="fuss-zeile fuss-zeile--leise">
				<nav class="fuss-navi" aria-label="Legal">
					<a href="privacy.html">Privacy</a>
				</nav>
<?php if ($besuche !== null): ?>
				<!-- Die Ziffernkästchen sind Bild, kein Text: Vorlesewerkzeuge bekommen
				     die Zahl daneben in einem Zug, statt sie Ziffer für Ziffer zu
				     buchstabieren. -->
				<p class="zaehler">
					<span aria-hidden="true"
						>Visits since August 2026 <?= zaehler_ziffern($besuche) ?></span
					>
					<span class="nur-vorlesen"
						>Visits since August 2026: <?= number_format($besuche) ?></span
					>
				</p>
<?php endif; ?>
			</div>
		</footer>
	</body>
</html>
