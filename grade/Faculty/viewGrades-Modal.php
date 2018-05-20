
  <!-- Modal -->
	<div class="modal fade" id="viewGrades-Modal" role="dialog" >
		<div class="modal-dialog">
			<!-- Modal content-->
			<form role="form" method="POST"> <!--Starting FORM-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-primary" id="subject-title">Subject - Grades</h4>
				</div>
				<div class="modal-body">					
					<table class="table table-condensed table-hover table-bordered">
						<thead>
							<tr style="background-color: #59b953;color: white;">
								<th>StudentID</th><th>Name</th><th class="text-center">MidTerm</th><th class="text-center">Final</th>
							</tr>
						</thead>
						<tbody id="subject-grades"></tbody>
					</table>
					
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-md" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div><!----Modal Dialog ---->
	</div>
