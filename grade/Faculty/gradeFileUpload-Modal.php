
  <!-- Modal -->
	<div class="modal fade" id="gradeFileUpload-Modal" role="dialog" data-backdrop="static">
		<div class="modal-dialog" style="margin-top: 8%;">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-primary" id="subject-title">UPLOADED FILE</h4>
				</div>
				<div class="modal-body">
					<div id="uploadError"></div>
					<div class="space-4"></div>
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>No.</th><th>Student ID</th><th>Name</th><th>Grade</th>
							</tr>
						</thead>
						<tbody id='gradeFileUpload-tableBody'>
							
						</tbody>
					</table>
					<small style="text-align: left">FileName: <span id="fileName"></span></small>
				</div>
				<div class="modal-footer" >
					<button class='btn btn-success' id='csvSubmit' data-dismiss="modal">Submit</button>
					<button class='btn btn-danger' data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div><!----Modal Dialog ---->
	</div>