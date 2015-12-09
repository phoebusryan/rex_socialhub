<h3>Plattformen - Parameter</h3>
<p>Unterschiedliche Plattformen, benötigen unterschiedliche Parameter. Facebook benötigt als Beispiel eine URL (u=), einen Titel (t=) und eine Redirect-URL (redirec_url=)</p>
<p>Jeder Parameter muss in einer neuen Zeile notiert werden.</p> 
<h3>Share-Vars</h3>
<p>Folgende Variablen können genutzt werden:</p>
<ul>
  <li>{{SHARE_URL}}: wird ersetzt durch die URL ohne Parameter.</li>
  <li>{{SHARE_TITLE}}: wird ersetzt durch den Seitennamen - Artikelnamen.</li>
</uL>
<p>Variablen in der URL können wie folgt in einer Slice-Ausgabe umgeschrieben werden:</p>
<code>
<?php highlight_file('codes/share_vars.php');?>
</code>