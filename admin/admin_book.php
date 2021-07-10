<?php

//appointment.php

include('../class/Appointment.php');

$object = new Appointment;

if(!isset($_SESSION['admin_id']))
{
    header('location:'.$object->base_url.'');
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Admin Appointment Book</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col-sm-6">
                            		<h6 class="m-0 font-weight-bold text-primary">Book Appointment</h6>
                            	</div>
                            	<div class="col-sm-6" align="right">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row input-daterange">
                                                <div class="col-md-6">
                                                    <input type="text" name="start_date" id="start_date" class="form-control form-control-sm" readonly />
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="end_date" id="end_date" class="form-control form-control-sm" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <button type="button" name="search" id="search" value="Search" class="btn btn-info btn-sm"><i class="fas fa-search"></i></button>
                                                &nbsp;<button type="button" name="refresh" id="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="appointment_table">
                                    		      			<tr>
                                <th>Doctor Name</th>
                                <th>Education</th>
                                <th>Speciality</th>
                                <th>Appointment Date</th>
                                <th>Appointment Day</th>
                                <th>Available Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="edit_appointment_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">View Appointment Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="appointment_details"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_appointment_id" id="hidden_appointment_id" />
                    <input type="hidden" name="action" value="change_appointment_status" />
                    <input type="submit" name="save_appointment" id="save_appointment" class="btn btn-primary" value="Save" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php

include('footer.php');

?>

<div id="appointmentModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="appointment_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Make Appointment</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div id="appointment_detail"></div>
                    <div class="form-group">
                        <label><b>Reasone for Appointment</b></label>
                        <textarea name="reason_for_appointment" id="reason_for_appointment" class="form-control" required rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_doctor_id" id="hidden_doctor_id" />
                    <input type="hidden" name="hidden_doctor_schedule_id" id="hidden_doctor_schedule_id" />
                    <input type="hidden" name="action" id="action" value="book_appointment" />
                    <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Book" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>

$(document).ready(function(){

    var dataTable = $('#appointment_list_table').DataTable({
        "processing" : true,
        "serverSide" : true,
        "order" : [],
        "ajax" : {
            url:"appointment_action.php",
            type:"POST",
            data:{appointment_action:'fetch_schedule'}
        },
        "columnDefs":[
            {
                "targets":[6],              
                "orderable":false,
            },
        ],
    });

    $(document).on('click', '.get_appointment', function(){

        var doctor_schedule_id = $(this).data('doctor_schedule_id');
        var doctor_id = $(this).data('doctor_id');

        $.ajax({
            url:"appointment_action.php",
            method:"POST",
            data:{appointment_action:'make_appointment', doctor_schedule_id:doctor_schedule_id},
            success:function(data)
            {
                $('#appointmentModal').modal('show');
                $('#hidden_doctor_id').val(doctor_id);
                $('#hidden_doctor_schedule_id').val(doctor_schedule_id);
                $('#appointment_detail').html(data);
            }
        });

    });

    $('#appointment_form').parsley();

    $('#appointment_form').on('submit', function(event){

        event.preventDefault();

        if($('#appointment_form').parsley().isValid())
        {

            $.ajax({
                url:"appointment_action.php",
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                beforeSend:function(){
                    $('#submit_button').attr('disabled', 'disabled');
                    $('#submit_button').val('wait...');
                },
                success:function(data)
                {
                    $('#submit_button').attr('disabled', false);
                    $('#submit_button').val('Book');
                    if(data.error != '')
                    {
                        $('#form_message').html(data.error);
                    }
                    else
                    {   
                        window.location.href="admin_book.php";
                    }
                }
            })

        }

    })

});

</script>