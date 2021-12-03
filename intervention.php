<?php
$db="dunkerque";
$dbhost="localhost";
$dbport=3306;
$dbuser="myadmin";
$dbpasswd="Serveur123";

$pdo = new PDO('mysql:host='.$dbhost.';port='.$dbport.';dbname='.$db.'', $dbuser, $dbpasswd);
$pdo->exec("SET CHARACTER SET utf8");
$stmt = $pdo->prepare("SELECT IdOpe, NomOpe, BateauSauvetage, BateauSecouru, DateOpe, Description, Lieu FROM Intervention i");
$stmt->execute();
$rows = [];
while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)){
    $rows[] = array_combine(['IdOpe','NomOpe', 'BateauSauvetage', 'BateauSecouru', 'DateOpe', 'Description','Lieu'], $row);
    $stmtequi = $pdo->prepare("SELECT DateNaissance, DateDeces, LieuNaissance, LieuDeces, Poste, Medailles FROM Personne p JOIN InterventionPersonne ip ON p.IdPersonne = ip.IdIntervention JOIN Itervention i ON i.IdOpe = ip.IdOpe WHERE IdOpe = ".$rows['IdOpe']);
    $stmtequi->execute();
    $equipage = [];
    while ($rowequi = $stmtequi->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)){
        array_push($equipage,array_combine(['DateNaissance', 'DateDeces', 'LieuNaissance', 'LieuDeces', 'Poste', 'Medailles'], $rowequi));
    }
    $rows['Equipage'] = $equipage;
}
echo json_encode($rows);
$pdo = null;
?>