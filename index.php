<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" integrity="sha384-zDnhMsjVZfS3hiP7oCBRmfjkQC4fzxVxFhBx8Hkz2aZX8gEvA/jsP3eXRCvzTofP" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
</head>

<body style="background-color:ivory ">
<div class="container">
    <!-- header panel -->
    <div class="row">
        <div class="col">
            <h1 style="text-align:center">
                Panel de Reserva de Pasajes
            </h1>
        </div>
    </div>

    <!-- Buttons to open the modal and refresh the table-->
    <div class="row" style="margin-bottom: 0.5em;">
        <div>
            <button type="button" class="btn btn-secondary btn_add_reservation" data-toggle="modal" data-target="#modal_reservations">
                <i class="fas fa-plus"> Agregar</i>
            </button>
            <button type="button" class="btn btn-secondary btn-table-refresh">
                <i class="fas fa-sync-alt"> Refrescar</i>
            </button>
        </div>
    </div>

    <!-- Reservations table -->
    <div class="row">
        <table id="reservations_table"  class="display table" style="width:100%">
            <thead>
            <tr>
                <th data-field="departure_city">Ciudad Salida</th>
                <th data-field="departure_date">Fecha/Hora Salida</th>
                <th data-field="arrival_city">Ciudad Llegada</th>
                <th data-field="arrival_date">Fecha/Hora Llegada</th>
                <th data-field="airline">Aerolinea</th>
                <th data-field="reservation_expire_date">Vencimiento de Reserva</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- The Modal -->
    <div class="modal" id="modal_reservations">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Reserva</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="modal-body" id="form_abm">
                        <form id="reservation_form">
                            <div class="form-group row">
                                <label for="departure_city_id" class="col-sm-4 col-form-label">Ciudad Origen</label>
                                <select class="form-control" name="departure_city_id" id="departure_city_id" required></select>
                            </div>
                            <div class="form-group row">
                                <label for="arrival_city_id" class="col-sm-4 col-form-label">Ciudad Destino</label>
                                <select class="form-control" name="arrival_city_id" id="arrival_city_id" required></select>
                            </div>
                            <div class="form-group row">
                                <label for="departure_date" class="col-sm-4 col-form-label">Fecha de Salida</label>
                                <input type="text" class="form-control datepicker" name="departure_date" id="departure_date" data-toggle="datepicker" placeholder="Elije una fecha..." required>
                            </div>
                            <div class="form-group row">
                                <label for="airline_id" class="col-sm-4 col-form-label">Aerolinea</label>
                                <select class="form-control" name="airline_id" id="airline_id" required></select>

                            </div>
                        </form>
                        <div class="form-group row modal_error" style="display: none;">
                            <span id="modal_error_msg"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="dialog_acept">Aceptar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="resources/custom.js"></script>
<script>
    let __HOST__ = "http://<?php echo $_SERVER['HTTP_HOST']; ?>";
</script>

</body>
</html>
