<!-- Modal -->
<div class="modal fade" id="studentProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Your Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- create account form -->
                    <form action="index.php" method="post" id="account_create">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Student Name</label>
                            <input type="text" name="student_name" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter Your Name" required>
                        </div>
                
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Student Phone</label>
                            <input type="tel" name="student_phone" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter Your Phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Student Email</label>
                            <input type="email" name="student_email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter Your Email" 
                                <?php
                                    if(isset($_GET["student_email"])){
                                        echo "value=".$_GET["student_email"];
                                    }
                                ?> readonly required>
                        </div>
                        <label class="form-check-label mb-2" for="gender">Select Your Gender</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="M" required>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="F" required>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="other" value="O" required>
                            <label class="form-check-label" for="other">Other</label>
                        </div><br>
                        <label class="form-check-label my-2" for="stream">Select Your Stream</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="stream" id="bca" value="BCA" required>
                            <label class="form-check-label" for="bca">BCA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="stream" id="bba" value="BBA" required>
                            <label class="form-check-label" for="bba">BBA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="stream" id="mca" value="MCA" required>
                            <label class="form-check-label" for="mca">MCA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="stream" id="mba" value="MBA" required>
                            <label class="form-check-label" for="mba">MBA</label>
                        </div>
                        <select class="form-select mt-3" name="student_semester" aria-label="Default select example" required>
                            <option>Select Your Semester..</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                            <option value="3">Third</option>
                            <option value="4">Fourth</option>
                            <option value="5">Fifth</option>
                            <option value="6">Sixth</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="account_create" name="createAccount" value="createAccount"
                        class="btn btn-primary">Create Account</button>
                </div>
            </div>
        </div>
    </div>