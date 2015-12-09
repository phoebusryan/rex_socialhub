# Social Media Manager

Lädt die Timelines von Facebook, Twitter und Instagram in die Redaxo-Datenbank. Twitter und Instagram können zusätzlich Hashtags suchen und in die Datenbank speichern.

Facebook hat keine Hashtag-API mehr. Daher wird dieses Feature nicht eingebaut.

## Installation

Socialhub benötigt Plugins damit es funktionieren kann. Jedes Plugin fügt Cronjobs hinzu, die von den jeweiligen Plattformen Daten laden und in Redaxo speichern.

Alle Einträge können aktiviert/deaktiviert bzw. hervorgehoben werden. Für einige Plattformen kann auch eine Hashtag-Suche aktiviert werden.

## Frontend

Die Ausgabe der Einträge im Frontend funktioniert über `REX_SOCIAL[]`. Dabei können folgende Werte übermittelt werden:

<table width="100%">
	<tr>
		<th>Option = Default</th>
		<th>Mögliche Werte</th>
		<th>Beschreibung</th>
	</tr>
	<tr>
		<td>type=timeline</td>
		<td>timeline | hashtags</td>
		<td>Definiert welche Einträge geladen werden sollen</td>
	</tr>
	<tr>
		<td>template=false</td>
		<td>false | true</td>
		<td>Soll das Grid-Template geladen werden? False = wird geladen.</td>
	</tr>
	<tr>
		<td>from = null</td>
		<td>twitter,facebook,instagram,other_plugins</td>
		<td>Lädt Einträge für die jeweiligen Plattformen. Die Plattformen müssen kommagetrent notiert werden</td>
	</tr>
	<tr>
		<td>limit = 0</td>
		<td>[0-9]+</td>
		<td>Ein Limit von 10 zeigt die 10 neuesten Einträge an.</td>
	</tr>
</table>

### Share-Buttons (Beta1)

Mit dem Plugin Share können Share-Buttons zu Slices hinzugefügt werden. In den Einstellungen kann definiert werden, in welchen CTypes und für welche Module diese Buttons generiert werden sollen.

Da unterschiedliche Plattformen unterschiedliche Parameter benötigen um Inhalte zu teilen, muss jeder Plattform die spezifischen Parameter Umbruchgetrennt zugeordnet werden. Dabei können folgende Variablen in den Parametern verwendet werden:

<table width="100%">
	<tr>
		<th>Variable</th>
		<th>Beschreibung</th>
	</tr>
	<tr>
		<td>{{SHARE_URL}}</td>
		<td>Wird durch die aktuelle URL ersetzt.</td>
	</tr>
	<tr>
		<td>{{SHARE_TITLE}}</td>
		<td>Wird ersetzt durch: Seitennamen - Artikelname</td>
	</tr>
</table>

#### Variablen überschreiben
 
Alle Variablen können in einem Slice wie folgt überschrieben werden:

```
<?php 
	socialhub_share::setConfig('share_url','http://www.domain.tld');
	socialhub_share::setConfig('share_title','Ein neuer Titel');
?>
```

### Ohne Grid laden

```
<div id="own_grid">
	REX_SOCIAL[type=hashtags limit=50 from=twitter,instagram template=1]
</div>
```

## Hub

### Facebook

Facebook benötigt lediglich eine öffentlich zugängliche Seite. Danach werden alle Posts der Seite geladen.

### Instagram

#### Client ID

Die Client ID ist mindestens nötig, um Hashtags suchen zu können. Hierzu muss eine App in Instagram erstellt, oder eine ID von einer vorhandenen App eingetragen werden.

#### Access Token

Der Access Token wird benötigt, um die Einträge von Profilen zu laden. Access Token können z.B. hier erstellt werden: http://instagram.pixelunion.net/. Der Access Token wird für den Account erstellt mit dem der Benutzer sich einloggt bei der Generierung.

### Twitter

Twitter benötigt eine App, die unter https://apps.twitter.com erstellt werden muss. Es kann eine bereits verwendete App genutzt werden. Um eine App erstellen zu können, muss der Entwickler seine Mobile-Nummer registrieren.

Nach der Erstellung benötigt es `Consumer Key (API Key)`, `Consumer Secret (API Secret)`, `Access Token` und den `Access Token Secret`. Der Zugang zu diesen Einstellungen, sollten nur Admins bekommen.

## @ToDo

- Ausgabe Frontend optimieren
- Ausgabe Backend optimieren
- Alte Einträge entfernen
- Ausgabe-Sorieren / Mischen

### Sobald es erforderlich wird:

- Einträge verfassen und hochladen
- Slices teilen mit Account XY