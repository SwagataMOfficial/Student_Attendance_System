<!-- Modal -->
<div class="modal fade" id="teacherProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Your Account</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- create account form -->
                <form action="index.php" method="post" id="teacherAccountCreate">

                    <div class="mb-3">
                        <label for="t_name" class="form-label">Teacher Name</label>
                        <input type="text" name="teacher_name" class="form-control" id="t_name"
                            aria-describedby="emailHelp" placeholder="Enter Your Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="t_phone" class="form-label">Teacher Phone</label>
                        <input type="tel" name="teacher_phone" class="form-control" id="t_phone"
                            aria-describedby="emailHelp" placeholder="Enter Your Phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label disabled">Teacher Email</label>
                        <input type="t_email" name="teacher_email" class="form-control" id="t_email"
                            aria-describedby="emailHelp" placeholder="Enter Your Email" 
                            <?php
                                if (isset($_GET["teacher_email"])) {
                                    echo "value=" . $_GET["teacher_email"];
                                }
                            ?> readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label mb-2">Select Your Gender</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="t_male" value="M">
                            <label class="form-check-label" for="t_male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="t_female" value="F">
                            <label class="form-check-label" for="t_female">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="t_other" value="O">
                            <label class="form-check-label" for="t_other">Other</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label mb-2">Are You HOD?</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="hod_select" id="yes" value="yes">
                            <label class="form-check-label" for="yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="hod_select" id="no" value="no">
                            <label class="form-check-label" for="no">No</label>
                        </div>
                    </div>
                    <div id="department" style="display: none;">
                        <label class="form-check-label mb-2">Select Your Department</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="department" id="t_bca" value="BCA">
                            <label class="form-check-label" for="t_bca">BCA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="department" id="t_bba" value="BBA">
                            <label class="form-check-label" for="t_bba">BBA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="department" id="t_mca" value="MCA">
                            <label class="form-check-label" for="t_mca">MCA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="department" id="t_mba" value="MBA">
                            <label class="form-check-label" for="t_mba">MBA</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="teacherAccount" form="teacherAccountCreate" value="teacherAccount"
                    class="btn btn-primary">Create Account</button>
            </div>
        </div>
    </div>
</div>
<script>
    const hod_yes = document.getElementById("yes");
    const hod_no = document.getElementById("no");
    hod_yes.addEventListener('change', ()=>{
        document.getElementById("department").style.display = "block";
    });
    hod_no.addEventListener('change', ()=>{
        document.getElementById("department").style.display = "none";
    });
</script>