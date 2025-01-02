<?php 
session_start();
include("header_classe_studente.php");

include("../../share/funzioni2_1.php");

if (!isset($_SESSION['Username']))
{
 echo "Non hai i permessi per accedere alla pagina";
}else {
	$id_utente = $_SESSION['id_utente'];
	$params=[
		['value' => $id_utente]
	]; 
	$query="select count(*) as tot from ct_utenti where id_utente=? and (fk_tipo_utente=2 or fk_tipo_utente=3)";
	$row=eseguiQueryPrepareOne($query,$params);
	
	if($row["tot"]==0) {
		echo "<div class='alert alert-danger'>Non hai i permessi per accedere alla pagina: non sei uno studente</div>";
	}
	else {
		if(!isset($_SESSION["id_classe"])) {
			echo "<div class='alert alert-danger'>Errore: nessuna classe selezionata! <strong><a href='./homepage_studente.php'>INDIETRO</a></strong></div>";
		}
		else {
			$id_classe=$_SESSION["id_classe"];
			$params=[
				['value' => $id_classe],
				['value' => $id_utente]
			]; 
			$query="select count(*) as tot from ct_utenti_classi where fk_classe=? and fk_utente=?";
			$row=eseguiQueryPrepareOne($query,$params);
			if($row<1) {
				echo "<div class='alert alert-danger'>Errore: l'utente non può accedere a questa classe <strong><a href='./homepage_studente.php'>INDIETRO</a></strong></div>";
			}
			else {
				$params=[
					['value' => $id_classe]
				]; 
				$query="select * from ct_classi inner join ct_anni_scolastici on fk_anno_scolastico=id_anno where ct_classi.id_classe=?";
				$row=eseguiQueryPrepareOne($query,$params);
		

?>

			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">

				<!-- Main Content -->
				<div id="content">

					<!-- Topbar -->
					<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

						<!-- Sidebar Toggle (Topbar) -->
						<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
							<i class="fa fa-bars"></i>
						</button>

						<!-- Topbar Search -->
						

						<!-- Topbar Navbar -->
						<ul class="navbar-nav ml-auto">

							<!-- Nav Item - Search Dropdown (Visible Only XS) -->
							<li class="nav-item dropdown no-arrow d-sm-none">
								<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-search fa-fw"></i>
								</a>
								<!-- Dropdown - Messages -->
								<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
									aria-labelledby="searchDropdown">
									<form class="form-inline mr-auto w-100 navbar-search">
										<div class="input-group">
											<input type="text" class="form-control bg-light border-0 small"
												placeholder="Search for..." aria-label="Search"
												aria-describedby="basic-addon2">
											<div class="input-group-append">
												<button class="btn btn-primary" type="button">
													<i class="fas fa-search fa-sm"></i>
												</button>
											</div>
										</div>
									</form>
								</div>
							</li>

							<?php
							include("alerts_studenti.php");
							?>

							<div class="topbar-divider d-none d-sm-block"></div>

							<!-- Nav Item - User Information -->
							<li class="nav-item dropdown no-arrow">
								<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="mr-2 d-none d-lg-inline text-gray-600 small">
									
									</span>
									<img class="img-profile rounded-circle"
										src="./img/undraw_profile_2.svg">
								</a>
								<!-- Dropdown - User Information -->
								<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
									aria-labelledby="userDropdown">
									<a class="dropdown-item" href="../dati_studente.php">
										<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
										Profilo
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
										<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
										Logout
									</a>
								</div>
							</li>

						</ul>

					</nav>
					<!-- End of Topbar -->

					<!-- Begin Page Content -->
					<div class="container-fluid">
					<?php
					$id_quest=$_GET["id_quest"];
					$params=[
						['value' => $id_quest]
					]; 
					$query="select * from ct_quest where id_quest=?";
					$row=eseguiQueryPrepareOne($query,$params);
					$consegnato="no";
					if(isset($_GET["consegnato"])) {
						$consegnato=$_GET["consegnato"];
					}
					
					if($consegnato=="ok") {
					?>
					<!-- Page Heading -->
						
							<div class="alert alert-success"><strong>Esercizio completato correttamente!</strong></div>
							
						
					<?php } ?>

						<!-- Page Heading -->
						<div class="d-sm-flex align-items-center justify-content-between mb-4">
							<img class="img_quest_small" src="<?php echo $row["image_quest"];?>" />
							<h1 class="h3 mb-0 text-gray-800"><strong style="color:#f21844"><?php echo $row["nome_quest"]."</strong>";?></h1>
							<div>
							<a href="student_quest.php" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                                class="fas fa-arrow-left fa-sm text-white-50" style="margin-right:0.5vw;width:2vw"></i>Back</a>
							</div>
						</div>

						

						

						<!-- DataTales Example -->
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Capitoli</h6>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th style="width:5%">Progr</th>
												<th style="width:20%">Nome Capitolo</th>
												<th style="width:20%">Argomento</th>
												<th style="width:15%">Tipologia</th>
												<th style="width:10%">Punti esperienza</th>
												<th style="width:10%">Enter</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$params = [["value"=>$id_quest]];
										$query="select * from ((((ct_quest inner join ct_esercizi_quest on fk_quest=id_quest) inner join ct_esercizi on fk_esercizio=id_esercizio) inner join ct_argomenti on id_argomento=fk_argomento) inner join ct_tipi_esercizio on id_tipo_esercizio=tipo_esercizio) inner join ct_classi_esercizi_attivi as cea on cea.fk_esercizio=id_esercizio where id_quest=? and attivo=1 order by progressivo";
										$resultset=eseguiQueryPrepareMany($query,$params);
										$params=[
											['value' => $id_utente],
											['value' => $id_classe]
										]; 
										$query_stud="select * from ct_studenti inner join ct_studenti_classi on id_studente=fk_studente where fk_utente=? and fk_classe=?";
										$row_stud=eseguiQueryPrepareOne($query_stud,$params);
										$id_studente=$row_stud["id_studente"];
										
										foreach($resultset as $row_s) {
											
											$params = [["value"=>$id_studente],["value"=>$row_s["id_esercizio"]]];
											$query_cons="select * from ct_consegne_studenti where fk_studente=? and fk_esercizio=?";
											$row_cons=eseguiQueryPrepareOne($query_cons,$params);
											$text_button="Compila";
											$stile="info";
											if(!$row_cons) {
												echo "<tr>";
												$text_button="Esegui esercizio";
												$stile="info";
											}
											else {
												if($row_cons["valutato"]==0) {
													echo "<tr style='background-color:#dbfeff'>";
													$text_button="Visualizza consegna";
													$stile="primary";
												}
												else {
													echo "<tr style='background-color:#82fa90'>";
													$text_button="Visualizza voto";
													$stile="danger";
												}
												
											}
												
											echo "<td>$row_s[progressivo]</td>";
											echo "<td>$row_s[nome_capitolo]</td>";
											echo "<td>$row_s[nome_argomento]</td>";
											echo "<td>$row_s[tipo]</td>";
											echo "<td>$row_s[punti_esperienza]</td>";
											?>
											<td style="text-align:center"><a href="vedi_esercizio_studente.php?id_esercizio=<?php echo $row_s["id_esercizio"];?>&id_quest=<?php echo $row_s["id_quest"];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm btn-<?php echo $stile;?>"><i
                                class="fas fa-feather fa-sm text-white-50 " style="margin-right:10px;"></i><?php echo $text_button;?></a></td>
											</tr><?php
											
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

					</div>

				</div>
				<!-- End of Main Content -->

				<!-- Footer -->
				<footer class="sticky-footer bg-white">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>Developed by prof. Danese</span>
						</div>
					</div>
				</footer>
				<!-- End of Footer -->

			</div>
			<!-- End of Content Wrapper -->

		</div>
		<!-- End of Page Wrapper -->

		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>

		<!-- Logout Modal-->
		<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<a class="btn btn-primary" href="../../logout.php">Logout</a>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Add Studente Modal-->
		<div class="modal fade" id="addQuestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Inserisci nuova quest</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
				  <div class="modal-body">
					<div id="mod_quest">
						<div class='row'>
						<div class='col-md-2' style='padding-top:10px'>
						<label for='nome_quest_mod'>Nome Quest</label></div>
						<div class='col-md-10' style='padding-top:10px'><input style='width:100%' type='text' id='nome_quest_mod' />
						</div>
						</div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="update_quest();">Salva</button>
				  </div>
				</div>
			</div>
		</div>

		<!--Script per aggiunta/modifica quest-->
		<script src="./js/mod_quest.js" ></script>

		<!-- Bootstrap core JavaScript-->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

		<!-- Core plugin JavaScript-->
		<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

		<!-- Custom scripts for all pages-->
		<script src="js/sb-admin-2.min.js"></script>

		<!-- Page level plugins -->
		<script src="vendor/chart.js/Chart.min.js"></script>
		
		<!-- Page level plugins -->
		<script src="vendor/datatables/jquery.dataTables.min.js"></script>
		<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

		<!-- Page level custom scripts -->
		<script src="js/demo/datatables-demo.js"></script>

		<!-- Page level custom scripts -->
		<script src="js/demo/chart-area-demo.js"></script>
		<script src="js/demo/chart-pie-demo.js"></script>

	</body>

	</html>
<?php }}}} ?>