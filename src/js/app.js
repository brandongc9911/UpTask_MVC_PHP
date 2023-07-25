(function () {
    if (document.querySelector('.opciones')) {
        const opciones = document.querySelectorAll('.opciones')
        const ulopciones = document.querySelectorAll('.ul-opciones')
        const formulario = document.querySelectorAll('.form-opcion');
        ulopciones.forEach(ul => {
            opciones.forEach(opcion => {
                opcion.addEventListener('click', function (opcionEvent) {
                    if (opcionEvent.target.dataset.proyecto === ul.dataset.proyecto) {
                        
                        ul.classList.toggle('show')
                        formulario.forEach(f => {
                            for (const x of f.children) {
                                x.addEventListener('click', function (e) {                                    
                                    if (e.target.innerText === 'Actualizar') {
                                        mostrarFormulario(e.target.dataset.proyecto,opcionEvent.target.dataset.proyecto)

                                    } else {
                                        Swal.fire({
                                            title: '¿Estas seguro?',
                                            text: "¡No podrás revertir esto!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: '¡Sí, eliminar!'
                                        }).then((result) => {
                                            if (result.value) {
                                                f.submit();
                                            }
                                        })
                                    }

                                })

                            }
                        })
                    }
                })
            })

        })
    }
})();


function mostrarFormulario(proyecto,id){   
    const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML =  `
            <form class="formulario nueva-tarea" method="POST" action="/proyecto/actualizar">
                <legend>Editar Proyecto</legend>
                <div class="campo">
                    <label>Proyecto</label>
                    <input type="hidden" value="${id}" id="${id}" name="id" />
                    <input type="text" value="${proyecto}" name="proyecto" placeholder="${proyecto ? 'Edita el proyecto' : ''}" id="proyecto"/>
                    </div>
                <div class="opciones">
                    <input type="submit" class="submit-actualizar-proyecto" value="Actualizar" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
            `;
        setTimeout(() => {
            const formulario = document.querySelector(".formulario")
            formulario.classList.add('animar')
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();

            // EVENT DELEGATION
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector(".formulario")
                formulario.classList.add('cerrar')
                setTimeout(() => {
                modal.remove();
                    
                }, 500);
            }

            if(e.target.classList.contains('submit-actualizar-proyecto')){
                const nombreProyecto = document.querySelector('#proyecto').value.trim();
        
                if(nombreProyecto === ''){
                    // MOSTRAR UNA ALERTA DE ERROR
                    mostrarAlerta('El nombre del proyecto es obligatorio', 'error', document.querySelector('.formulario legend') );

                    return;
                }
                proyecto = nombreProyecto
                
            const formulario = document.querySelector(".formulario")
               formulario.submit()
                
                
                
                
            }
        })
    document.querySelector('.dashboard').append(modal);
    
}

function mostrarAlerta(mensaje,tipo,referencia){
    // PREVIENE LA CREACION DE MULTIPLES ALERTAS
    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia){
        alertaPrevia.remove()
    }
    const alerta = document.createElement('DIV');
    alerta.classList.add('alerta',tipo);

    alerta.textContent = mensaje;

    // INSERTA LA ALERTA ANTES DEL LEGEND
    referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

    // ELIMINAR LA ALERTA DESPUES DE 5 SEG
    setTimeout(()=>{
        alerta.remove()
    }, 1000)
}



const mobileMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');
const proyectos = document.querySelectorAll('.proyecto');



if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function () {
        sidebar.classList.add('mostrar')
        proyectos.forEach(proyecto => {
            proyecto.style.display = "none";
        })
    })
}

if (cerrarMenuBtn) {
    cerrarMenuBtn.addEventListener('click', function () {
        sidebar.classList.remove('mostrar')
        proyectos.forEach(proyecto => {
            proyecto.style.display = "block";
            
        })
    })
}

