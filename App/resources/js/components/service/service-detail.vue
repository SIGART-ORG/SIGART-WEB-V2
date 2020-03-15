<template>
    <div id="service" class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Detalle de servicio - <strong>SERV001-550</strong></h3>
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
            <h3>Tareas</h3>
            <h4>Por Iniciar</h4>
            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Lijar Pared</td>
                        <td>Proceso de lijado para que la nueva pintura tenga fuerte agarre y no se descascaré.</td>
                        <td>
                            Luis Alfredo Rosales Echevarrría<br>
                            José Luis Zarate
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Lijar Pared</td>
                        <td>Proceso de lijado para que la nueva pintura tenga fuerte agarre y no se descascaré.</td>
                        <td>
                            Luis Alfredo Rosales Echevarrría<br>
                            José Luis Zarate
                        </td>
                    </tr>
                </tbody>
            </table>
            <h4>En Proceso</h4>
            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Lijar Pared</td>
                    <td>Proceso de lijado para que la nueva pintura tenga fuerte agarre y no se descascaré.</td>
                    <td>
                        Luis Alfredo Rosales Echevarrría<br>
                        José Luis Zarate
                    </td>
                    <td>15/03/2020 06:00 pm</td>
                </tr>
                </tbody>
            </table>
            <h4>Terminado</h4>
            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Validar</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Lijar Pared</td>
                    <td>Proceso de lijado para que la nueva pintura tenga fuerte agarre y no se descascaré.</td>
                    <td>
                        Luis Alfredo Rosales Echevarrría<br>
                        José Luis Zarate
                    </td>
                    <td>15/03/2020 06:00 pm</td>
                    <td>16/03/2020 06:00 pm</td>
                    <td class="action">
                        <ul class="list-inline justify-content-around">
                            <li class="list-inline-item">
                                <a class="view">
                                    <i class="fa fa-check"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="delete">
                                    <i class="fa fa-close"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            <h4>Tareas en observación</h4>
            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Observaciones</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Lijar Pared</td>
                    <td>Proceso de lijado para que la nueva pintura tenga fuerte agarre y no se descascaré.</td>
                    <td>
                        Luis Alfredo Rosales Echevarrría<br>
                        José Luis Zarate
                    </td>
                    <td>
                        <a class="text-danger" href="javascript:;">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;4
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <h4>Tareas Finalizadas</h4>
            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Aprobado</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Lijar Pared</td>
                    <td>Proceso de lijado para que la nueva pintura tenga fuerte agarre y no se descascaré.</td>
                    <td>
                        Luis Alfredo Rosales Echevarrría<br>
                        José Luis Zarate
                    </td>
                    <td>15/03/2020 06:00 pm</td>
                    <td>16/03/2020 06:00 pm</td>
                    <td>17/03/2020 10:00 am</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "service-detail",
        created() {

        }
    }
</script>

<style scoped>

</style>
