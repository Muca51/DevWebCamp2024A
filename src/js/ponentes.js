(function(){
    const ponentesInput = document.querySelector('#ponentes');
    
    if(ponentesInput) {
        let ponentes = [];
        let ponentesFiltrados = [];

        const listadoPonentes = document.querySelector('#listado-ponentes')
        const ponenteHidden = document.querySelector('[name=ponente_id]')

        obtenerPonentes();

        ponentesInput.addEventListener('input', buscarPonentes)

        if(ponenteHidden.value) {
           (async() => {
                const ponente = await obtenerPonente(ponenteHidden.value)
                const { nombre, apellido} = ponente
                
                //Insertar en el Html
                const ponenteDOM = document.createElement('LI');
                ponenteDOM.classList.add('listado-ponentes__ponente', 'listado-ponentes__ponente--seleccionado');
                ponenteDOM.textContent = `${nombre} ${apellido}`

                listadoPonentes.appendChild(ponenteDOM)
                
           })()
        }

        async function obtenerPonentes () {

            const url = `/api/ponentes`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            formatearPonentes(resultado);
            
        } 

        async function obtenerPonente(id) {
            console.log(`Obteniendo ponente con ID: ${id}`);  // Verifica el ID
            const url = `/api/ponente?id=${id}`;
            try {
                const respuesta = await fetch(url);
                if (!respuesta.ok) {
                    throw new Error(`Error en la solicitud: ${respuesta.statusText}`);
                }
                const resultado = await respuesta.json();
                console.log("Datos del ponente:", resultado);  // Verifica los datos recibidos
                return resultado;
            } catch (error) {
                console.error("Error al obtener el ponente:", error);
                return null;
            }
        }
        

        function formatearPonentes (arrayPonentes = []) {
            ponentes = arrayPonentes.map( ponente => {
                return {
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`,
                    id: ponente.id
                }
            })

         
            
        }

        function buscarPonentes (e) {
            const busqueda = e.target.value;
            
            if(busqueda.length > 3) {
                const expresion = new RegExp(busqueda, "i");
                ponentesFiltrados = ponentes.filter(ponente => {
                    if(ponente.nombre.toLowerCase().search(expresion) != -1) {
                        return ponente;
                    }
                })

                
            } else {
                ponentesFiltrados = [];
            }

            mostrarPonentes();

        }

        function mostrarPonentes() {

           while(listadoPonentes.firstChild){
                listadoPonentes.removeChild(listadoPonentes.firstChild)
           }

           if(ponentesFiltrados.length > 0) {
                ponentesFiltrados.forEach(ponente => {
                    const ponenteHTML = document.createElement('LI');
                    ponenteHTML.classList.add('listado-ponentes__ponente')
                    ponenteHTML.textContent = ponente.nombre;
                    ponenteHTML.dataset.ponenteId = ponente.id
                    ponenteHTML.onclick = seleccionarPonente
                

                    //Añadir al DOM

                    listadoPonentes.appendChild(ponenteHTML)

                })

            } else {
                const noResultados = document.createElement('P')
                noResultados.classList.add('listado-ponentes__no-resultado')
                noResultados.textContent = "No hay resultado para tu búsqueda"

                listadoPonentes.appendChild(noResultados)
            }
        }

        function seleccionarPonente(e) {
            const ponente = e.target;
            //Remover la clase previa
            const ponentePrevio = document.querySelector('.listado-ponentes__ponente--seleccionado')
            if(ponentePrevio) {
                ponentePrevio.classList.remove('listado-ponentes__ponente--seleccionado')
            }

            ponente.classList.add('listado-ponentes__ponente--seleccionado')

            ponenteHidden.value = ponente.dataset.ponenteId


        }


    }
})();