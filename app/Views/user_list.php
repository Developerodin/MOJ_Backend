<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<div class="content-body">
	<!-- row -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-9 col-xxl-12">
				<div class="row">
					<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12">
			
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h4 class="card-title">Users Queue</h4>
							</div>
							<div class="card-body">
								<table id="example" class="display " style="background-color: #fff;">
									<thead>
										<tr>

											<th><strong>Id</strong></th>
											<th><strong>Image</strong></th>
											<th><strong>Name</strong></th>
											<th><strong>Contact</strong></th>
											<th><strong>Address</strong></th>
											<th><strong>STATUS</strong></th>
											<th style="width:85px;"><strong>EDIT</strong></th>
										</tr>
									</thead>
									<?php if (!empty($users)) : ?>
										<tbody>
											<?php foreach ($users as $user) : ?>
											<tr>

												<td><b>
														<?= $user->id ?>
													</b></td>
												<td>
													<img src="upload/<?= $user->profile_picture ?>" alt="image"width="50"/>
												</td>
												<td>
													<?= $user->name ?>
						`						</td>
												<td>
													<strong>Phone :</strong><?= $user->mobile_number ?><br>
													<strong>Email :</strong><?= $user->email ?>
													
												</td>
												<td>
													<strong>Add :</strong><?= $user->address ?><br>
													<strong>City :</strong><?= $user->city ?><br>
													<strong>Country :</strong><?= $user->country ?><br>
												</td>

												<td class="recent-stats"><i class="fa fa-circle text-success me-1"></i>
													<?= $user->status ?>
												</td>
												<td>
													<a href="#" class="btn btn-primary shadow btn-xs sharp me-1"><i
															class="fa fa-pencil"></i></a>
													<a href="#" class="btn btn-danger shadow btn-xs sharp"><i
															class="fa fa-trash"></i></a>
												</td>
											</tr>
											<?php endforeach; ?>
											<?php else : ?>
											<tr>
												<td>
													<div class="form-check">
														<input class="form-check-input" type="checkbox" value=""
															id="flexCheckDefault-1">
														<label class="form-check-label" for="flexCheckDefault-1">

														</label>
													</div>
												</td>
												<td><b>$542</b></td>
												<td>Dr. Jackson</td>
												<td>01 August 2023</td>
												<td class="recent-stats"><i
														class="fa fa-circle text-success me-1"></i>Successful</td>
												<td>
													<a href="#" class="btn btn-primary shadow btn-xs sharp me-1"><i
															class="fa fa-pencil"></i></a>
													<a href="#" class="btn btn-danger shadow btn-xs sharp"><i
															class="fa fa-trash"></i></a>
												</td>
											</tr>
										</tbody>
										<?php endif; ?>
								</table>
							</div>
						</div>
						

						<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
						<script>
							var $j = jQuery.noConflict(); // Assign jQuery to $j
						</script>
						<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
						<script>
							$j(document).ready(function () {
								$j('#example').DataTable();
							});
						</script>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!--**********************************
            Content body end
        ***********************************-->


<!--**********************************
            Footer start
        ***********************************-->