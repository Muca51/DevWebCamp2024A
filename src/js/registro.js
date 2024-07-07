import Swal from "sweetalert2";

(function(){
    let eventos = [];
    const resumen = document.querySelector('#registro-resumen');
    if(resumen) {

        const eventosBoton = document.querySelectorAll('.evento__agregar');
        eventosBoton.forEach(boton => boton.addEventListener('click', seleccionarEvento));

        const formularioRegistro = document.querySelector('#registro');
        formularioRegistro.addEventListener('submit', submitFormulario)

        mostrarEventos();

        async function seleccionarEvento({target}){
            const eventoId = target.dataset.id;

            try {
                const response = await fetch(`/api/evento?id=${eventoId}`);
                const evento = await response.json();

                if (evento.error) {
                    Swal.fire({
                        title: 'Error',
                        text: evento.error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const diaId = evento.dia_id;
                const horaId = evento.hora_id;

                const conflictoHorario = eventos.some(evento => evento.dia_id === diaId && evento.hora_id === horaId);

                if (conflictoHorario) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ya tienes un evento programado para este día y horario',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (eventos.length < 5) {
                    target.disabled = true;
                    eventos = [...eventos, {
                        id: eventoId,
                        titulo: target.parentElement.querySelector('.evento__nombre').textContent.trim(),
                        dia_id: diaId,
                        hora_id: horaId
                    }];

                    mostrarEventos();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Máximo 5 Eventos por Registro',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Error fetching event data:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo obtener la información del evento',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }

        function mostrarEventos(){
            // Limpiar el HTML
            limpiarEventos();

            if(eventos.length > 0) {
                eventos.forEach( evento => {
                    const eventoDOM = document.createElement('DIV');
                    eventoDOM.classList.add('registro__evento');

                    const titulo = document.createElement('H3');
                    titulo.classList.add('registro__nombre');
                    titulo.textContent = evento.titulo;

                    const botonEliminar = document.createElement('BUTTON');
                    botonEliminar.classList.add('registro__eliminar');
                    botonEliminar.innerHTML = `<i class= "fa-solid fa-trash"></i>`;
                    botonEliminar.onclick = function () {
                        eliminarEvento(evento.id);
                    };

                    // Renderizar en el HTML
                    eventoDOM.appendChild(titulo);
                    eventoDOM.appendChild(botonEliminar);
                    resumen.appendChild(eventoDOM);
                });
            } else {
                const noRegistro = document.createElement('P')
                noRegistro.textContent = 'No hay aún eventos seleccionados, añade hasta 5 del lado izquierdo'
                noRegistro.classList.add('registro__texto')
                resumen.appendChild(noRegistro)
            }
        }

        function eliminarEvento(id) {
            eventos = eventos.filter( evento => evento.id !== id);
            const botonAgregar = document.querySelector(`[data-id="${id}"]`);
            botonAgregar.disabled = false;
            mostrarEventos();
        }

        function limpiarEventos() {
            while(resumen.firstChild) {
                resumen.removeChild(resumen.firstChild);
            }
        }

        async function submitFormulario(e) {
            e.preventDefault();

            //Obtener el regalo

            const regaloId = document.querySelector('#regalo').value
            const eventosId = eventos.map(evento => evento.id)

            //Validar que se seleccione al menos un evento y un regalo

            if(eventosId.length === 0 || regaloId === '' ) {
                Swal.fire({
                    title: 'Error',
                    text: 'Debe eligir al menos un evento y un regalo',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            //Objeto de formdata

            const datos = new FormData();
            datos.append('eventos', eventosId)
            datos.append('regalo_id', regaloId)
            

            const url = '/finalizar-registro/conferencias'
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            
            const resultado = await respuesta.json();

            if(resultado.resultado) {
                Swal.fire(
                    'Registro Éxitoso',
                    'Sus Eventos han quedado registrados, te esperamos en DevWebCamp',
                    'success' 
                ).then( () => location.href = `/boleto?id=${resultado.token}`)
                    
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Ha ocurrido un error',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then( () => location.reload() )
                

            }
        }
    }

})();
