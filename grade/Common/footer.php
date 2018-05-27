        <div id="footer">
            <footer>
                &copy; Copyrights &bullet; <?php echo date("Y")?> &bull; Alrights reserved. <br/>
                Thesis Advisee : Rafael Rosalejos,  
                Thesis Adviser : Prof. Winston M. Tabada
            </footer>
        </div>
        </body>

        <script>
            $(document).ready(function(){                
                // $("ul.link").find("li").on("click", function(){
                //     $("li").removeClass("active");
                //     $(this).addClass("active");
                // });
                // $("ul.link li").removeClass("active");
                // $("#f-courses").addClass("active");

                showInstructorSubjects();
                function getSeason(){
                    var year=$("#hidden_year").val();
                    var sem=$("#hidden_sem").val();
                    var season = year+""+sem;
                    return season;
                }
                function showInstructorSubjects(){
                    var season=getSeason();
                    $.post("../Model/Service.php",
                        {season:season, action: "getSubjectListByStaff"},
                        function(data){
                            $('#subjectList').html(data);
                        }
                    ); 
                }
                $('#subjectList').on('click', 'tr', function() {
                    var values = $(this).find('td').map(function() {
                        return $(this).text();
                    });
                    showClassInformation(values[0]);
                });
                function showClassInformation(offerID){
                    var season=getSeason();
                    $.post("../Model/Service.php",
                        {season:season, offerID:offerID, action: "getClassByOfferNo"},
                        function(data){
                            $('#class-info').html(data);
                        }
                    ); 
                    $('#class-info').show();
                }
                $("input[name='sem']").change(function(){
                    showInstructorSubjects();
                });

                $(document).on("click", "#viewGrades", function(){
                    var subjectTitle = $(this).data('subject');
                    var offerID = $(this).data('offer');
                    var season=getSeason();
                    $('#subject-title').html(subjectTitle);                    
                    $('#subject-grades').html("");

                    $.post("../Model/Service.php",
                        {season:season, offerID:offerID,action: "getGradeByOfferNo"},
                        function(data){
                            $('#subject-grades').html(data);
                        }
                    ); 
                });
               
            });
        </script>
</html>
<?php ob_end_flush(); ?>