
<?php
//on inclu le fichier connexion pour effectuer la connexion a la bdd
require('connexion.php');
try{

// pagination 
$nbrElementParPage = 4;

// mettre par defaut l'index sur la page 1
if(isset($_GET['page']) && !empty($_GET['page'])){
     $page = (int) strip_tags($_GET['page']);
   }else{
     $page = 1;
   }

   $debut=($page-1)* $nbrElementParPage;


$sql2 = "SELECT id,chemin,file_name from images order by id DESC limit $debut,$nbrElementParPage";

$pdo = new PDO($dsn, $dbusername, $dbpassword);
// on execute la requete sql
$stmt = $pdo->query($sql2);

// on récupére les lignes
$rows = $stmt->fetchAll();

$var = $pdo->prepare('SELECT COUNT(*) from images') ;
$var->execute();
$nbrDelignes = $var->fetch(PDO::FETCH_NUM);
$nbrDePage = ceil($nbrDelignes[0]/ $nbrElementParPage);

if($stmt === false){
     die("Erreur");
  }
  
 }catch (PDOException $e){
     echo $e->getMessage();
 }

 function insereBddDossier($pdo,$pname,$psize,$dest){
    $sql = "INSERT into images (file_name, size , chemin) VALUES ('$pname','$psize','$dest')";
    
     // dossier ou l'on va insserer les images 
    
     
    if ($_FILES['mon_fichier']['error'] > 0) $erreur = "Erreur lors du transfert";
     // upload de l'image dans le dossier 
    $resultat = move_uploaded_file($_FILES['mon_fichier']['tmp_name'],$dest.$_FILES['mon_fichier']['name']);
    
    if ($resultat) $pdo->query($sql) ; echo "Transfert réussi"; header("Refresh:0");
    }



if(isset($_FILES["mon_fichier"])){
$pname = $_FILES['mon_fichier']['name'];
$psize = $_FILES['mon_fichier']['size'];
$dest = "images/";



// on insert la valeur nom de l'image dans nom_image(bdd)
insereBddDossier($pdo,$pname,$psize,$dest);


}


?>


<!-- Code HTML-->


<center>
<h1>Televersement d'images</h1><br/>
 
 <form method="post" enctype="multipart/form-data">
      <label for="mon_fichier">Choissisez une image a televerser :</label> <br>
      <input type="file" name="mon_fichier" id="mon_fichier" accept="images/png, images/jpeg, images/jpg, images/gif" />
      <input type="submit" name="submit" value="Envoyer" />
 </form>

<!-- on va afficher image par image et l'id avec-->
<div id="listePagination">
    <?php $i=0; foreach($rows as $row): ?>
        <img src="<?= $row['chemin'],$row['file_name'] ; ?>" alt="" />
        <span><?= $row['id'] ; ?></span>
        
        <?php $i++; if($i%2==0){ ?> <br> <?php } ?>
        
    <?php endforeach; ?>
    </div>


    <!-- création des boutons en fonction du nombre de page-->
    <div id="pagination">
    <?php
    // affichage des chiffres
    for($i=1;$i<=$nbrDePage;$i++){
        if($page!=$i)
            echo "<a href='?page=$i'>$i</a>&nbsp;";
        else
            echo "<a>$i</a>&nbsp;";
    }

    ?>
    </div>






<center/>

<style>
<?php include 'style.css'; ?>
</style>
