<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
	.popup {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		padding-top: 5rem;
		margin-left: 8rem;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.4);
	}

	.popup-content {
		background-color: #fefefe;
		margin: 5% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
	}

	.close {
		color: #aaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.close:hover,
	.close:focus {
		color: black;
		text-decoration: none;
		cursor: pointer;
	}
</style>

<div class="content-body">
	<!-- row -->
	<div class="container-fluid">
		<div class="row">
			<div id="editPopup" class="popup">
				<div class="popup-content ">
					<div class="contianer p-4">
						<span class="close" onclick="closePopup()">&times;</span>
						<div class="col-md-12 text-start pb-3">
							<h2 class="bold">User info</h2>
							<br>
						</div>
						<div class="row text-start pb-3 align-content-center align-items-center"
							style="    justify-content: space-between;">
							<div class="col-md-8 row align-content-center align-items-center">
								<div class="col-md-1 text-end">
									<img src="images/user_img.png" alt="" width="100%">
								</div>
								<div class="col-md-7">
									<h4>Name here</h4>

									<p class="m-0">Secondary info</p>
								</div>
							</div>
							<div class="col-md-2">
								<button class="btn broder"
									style="padding:7px 15px;    border: 1px solid #f7a707;color:#f7a707;">Deactivate
									account</button>
							</div>

						</div>
						<h3>Personal info</h3>
					</div>

					<form id="editForm" action="<?= base_url('/auth/adminuser_update') ?>" method="POST"
						class="container">

						<!-- Hidden input field for user ID -->
						<input type="hidden" id="user_id" name="user_id" value>
						<input type="hidden" id="pin_code" name="pin_code" value>
						<input type="hidden" id="address" name="address" value>
						<input type="hidden" id="created_at" name="created_at" value>

						<!-- Input fields for editing user details -->
						<div class="row  d-flex justify-content-around py-3">
							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="name">First name:</label></div>
								<input type="text" id="name" name="name" value>
							</div>

							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="last_name">Last name:</label></div>
								<input type="text" id="last_name" name="last_name" value>
							</div>


							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="mobile_number">Date of birth:</label></div>
								<input type="text" id="dob" name="dob" value>
							</div>
							<div class="col-md-3 mt-3">
								<div class="col-md-12">
									<label for="gender">Gender:</label>
								</div>
								<select id="gender" name="gender" value>
									<option value="male">Male</option>
									<option value="female">Female</option>
									<option value="other">Other</option>
								</select>
							</div>
						</div>

						<div class="row justify-content-around  py-3">
							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="email">Email:</label></div>
								<input type="email" id="email" name="email" value>
							</div>



							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="country">Country:</label></div>
								<input type="text" id="country" name="country" value>
							</div>

							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="mobile_number">Mobile
										Number:</label></div>
								<input type="text" id="mobile_number" name="mobile_number" value>
							</div>
							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="state">State:</label></div>
								<input type="text" id="state" name="state" value>
							</div>

							<div class="col-md-3 mt-3">
								<div class="col-md-12"><label for="city">City:</label></div>
								<input type="text" id="city" name="city" value>
							</div>

							<div class="col-md-3 mt-3">
								<div class="col-md-12"> <label for="role">Role:</label></div>
								<input type="text" id="role" name="role" value>
							</div>
							<div class="col-md-3 mt-3">
								<div class="col-md-12"> <label for="role">Rewords:</label></div>
								<input type="text" id="points" name="points" value>
							</div>
						</div>

						<!-- Add more input fields for other user details -->
						<div class="btn d-flex justify-content-center ">
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>


			<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12">

				<div class="card">
					<div class="card-header border-0 pb-0">
						<h4 class="card-title">Users Queue</h4>
					</div>
					<div class="card-body">
						<?php if (!$users == null) :

							?>
						<table id="example" class="display " style="background-color: #fff;">
							<thead>
								<tr>

									<th><strong>Id</strong></th>
									<th><strong>Image</strong></th>
									<th><strong>Name</strong></th>
									<th><strong>Contact</strong></th>
									<th><strong>Address</strong></th>
									<th><strong>Rewords</strong></th>
									<th><strong>Status</strong></th>
									<th style="width:85px;"><strong>Edit</strong></th>
								</tr>
							</thead>


							<tbody>
								<?php 
								
								$json_user = json_encode($users);
								foreach ($users as $user) :
								
								?>

								<tr>

									<td><b>
											<?= $user->user_id ?>
										</b></td>
									<td>
										<img src="images/user_img.png" alt="image" width="50" />
									</td>
									<td>
										<?= $user->name ?>
										<?= $user->last_name ?>
									</td>
									<td>
										<strong>Phone : </strong>
										<?= $user->mobile_number ?><br>
										<strong>Email : </strong>
										<?= $user->email ?>

									</td>
									<td>
										<strong>Country : </strong>
										<?= $user->country ?><br>
										<strong>State : </strong>
										<?= $user->state ?><br>
										<strong>City : </strong>
										<?= $user->city ?><br>
									</td>
									<td>
										<?= $user->points ?>

									</td>
									<td class="recent-stats"><i class="fa fa-circle text-success me-1"></i>
										<?= $user->status ?>
									</td>
									<td>
										<a href="#" class="btn btn-warning shadow btn-xs sharp me-1 d-none"
											onclick="toggleLock(<?php echo $user->user_id; ?>)">
											<i class="fa fa-unlock"></i> <!-- Initially show lock icon -->
										</a>
										<a href="#" class="btn btn-info shadow btn-xs sharp"
											onclick="openPopup(<?php echo $user->user_id; ?>)">
											<i class="fa fa-eye"></i>
										</a>
										<a href="<?= base_url('/user_delete/' .$user->user_id) ?>"
											id="deleteLink<?= $user->user_id ?>"
											class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
								<script>
									document.getElementById("deleteLink<?= $user->user_id ?>").addEventListener('click', function (event) {
										event.preventDefault(); // Prevents the default action of clicking the link

										// Show an alert to confirm deletion
										if (confirm('Are you sure you want to delete this user?')) {
											// If user confirms, proceed with the deletion by redirecting to the delete API URL
											window.location.href = this.getAttribute('href');
										}
									});
								</script>
								<script>
									function toggleLock(userId) {

										var isLocked = true; // For example, initially locked
										isLocked = !isLocked;

										// Change button icon based on lock status
										var lockButton = document.querySelector('.btn-lock i');
										if (isLocked) {
											lockButton.classList.remove('fa-unlock');
											lockButton.classList.add('fa-lock');
											// Call API to lock the user
											fetch(`/users/status_d/${userId}`, { // Construct the API URL with user ID
												method: 'POST',
												headers: {
													'Content-Type': 'application/json'
												},
												body: JSON.stringify({
													status: 'locked' // You may want to send the status as well
												})
											})
												.then(response => {
													// Handle response if needed
												})
												.catch(error => {
													console.error('Error locking user:', error);
												});
										} else {
											lockButton.classList.remove('fa-lock');
											lockButton.classList.add('fa-unlock');
											// Call API to unlock the user
											fetch(`/users/status_e/${userId}`, { // Construct the API URL with user ID
												method: 'POST',
												headers: {
													'Content-Type': 'application/json'
												},
												body: JSON.stringify({
													status: 'unlocked' // You may want to send the status as well
												})
											})
												.then(response => {
													// Handle response if needed
												})
												.catch(error => {
													console.error('Error unlocking user:', error);
												});
										}
									}
									function openPopup(userId) {
										document.getElementById("editPopup").style.display = "block";
										document.getElementById("user_id").value = userId;
										populateFormFields(userId);
									}

									function closePopup() {
										document.getElementById("editPopup").style.display = "none";
									}
									var users = <?= json_encode($json_user, JSON_HEX_TAG); ?>;
									// Function to populate form fields with user data
									function populateFormFields(userId) {
										// Ensure users is an array
										var usersArray = JSON.parse(users); // Parsing the JSON string into a JavaScript object/array
									
										// Find the user with the specific userId
										var user = usersArray.find(function (u) {
											return u.user_id == userId;
										});
									
										// If the user is found, populate the form fields
										if (user) {
											document.getElementById("name").value = user.name || '';
											document.getElementById("address").value = user.address || '';
											document.getElementById("pin_code").value = user.pin_code || '';
											document.getElementById("points").value = user.points || '';
											document.getElementById("created_at").value = user.created_at || '';
											document.getElementById("last_name").value = user.last_name || '';
											document.getElementById("mobile_number").value = user.mobile_number || '';
											document.getElementById("email").value = user.email || '';
											document.getElementById("dob").value = user.dob || '';
											document.getElementById("gender").value = user.gender || '';
											document.getElementById("country").value = user.country || '';
											document.getElementById("state").value = user.state || '';
											document.getElementById("city").value = user.city || '';
											document.getElementById("role").value = user.role || '';
										} else {
											console.log("User not found");
										}
									}
								</script>
								<?php endforeach; ?>
						</table>
						<?php else : ?>
						<p>User not found</p>
						<?php endif; ?>
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