
  <!-- Modal -->
	<div class="modal fade" id="gradingPeriod-Modal" role="dialog" data-backdrop="static">
		<div class="modal-dialog" style="width: 400px; margin-top: 8%;">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-primary" id="period-title">Grading Period</h4>
				</div>
				<div class="modal-body">
					<form id="gradingPeriod-Form">
						<div class="row">
							<div class="col-md-2">
								<label>Semester:</label>
							</div>
							<div class="col-md-10">
								&nbsp;&nbsp;
								<label class="radio-inline">
								<input type="radio" name="gradeSubmission-sem" <?php echo $sem==1? 'checked': ''?> value=1 />1st Sem</label> 

								<label class="radio-inline"><input type="radio" name="gradeSubmission-sem"
								<?php echo $sem==2? 'checked': ''?> value=2 />2nd Sem</label>

								<label class="radio-inline"><input type="radio" name="gradeSubmission-sem"
									<?php echo $sem==3? 'checked': ''?> value=3 /> Summer</label>
							</div>
							<div class="clearfix"></div>
						    <div class="space-4"></div>	

							<div class="col-md-2">
								<label>Year: </label>
							</div>
							<div class="col-md-10">
								<select id="gradingPeriod_year" class="form-control"><?php echo $common->generateYear($syr); ?></select>
							</div>

							<div class="col-md-2">
								<label>Term: </label>
							</div>
							<div class="col-md-10">
								<select class="form-control" id="gradingPeriod-term">
									<option value="Midterm">Midterm</option>
									<option value="Final">Final</option>
								</select>
							</div>

							<div class="clearfix"></div>
						    <div class="space-6"></div>	
							<div class="col-md-12" style="border-top: 1px solid #e5e5e5;">
								<div class="space-4"></div>
								<p class="text-right">
									<button type="submit" class="btn btn-success btn-md" >Continue</button>
									<button type="submit" class="btn btn-danger btn-md" data-dismiss="modal">Close</button>
								</p>
							</div>
						</div>
				</form>
			</div>
		</div><!----Modal Dialog ---->
	</div>