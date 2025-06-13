# LinkedIn-Share
WordPress Plugin to share specific tab container content as URL on LinkedIn for reasons.



So funktioniert’s:
	•	align steuert über Wrapper-Klassen die Ausrichtung.
	•	height: 30px; width: auto; skaliert SVG/PNG proportional.
	•	Für Desktop gibt’s Scale (1.1) + Glanz-Sweep (sheen-desktop).
	•	Für Mobil nur ein sanftes Scale (1.05), ohne Glanzeffekt.



Example Usage:
[linkedin_share_button align_desktop="center" align_mobile="right" class="mein-zusatz-css" alt="Jetzt auf LinkedIn teilen"]

Parameter-Steuerungen:
align_desktop bestimmt die Ausrichtung ab einer Bildschirmbreite ≥ 768 px.
align_mobile gilt für Breiten < 768 px.
Beide Parameter akzeptieren nur left, center oder right.
Über class und alt kannst du zusätzliche CSS-Klassen und den Alt-Text anpassen.
