window.addEventListener("load", async function() {
    //Selección de elementos
    const formularioCreacion = document.getElementById("formulario_creacion_producto");
    const inputCodigo = document.getElementById("input_codigo_producto");
    const inputNombre = document.getElementById("input_nombre_producto");
    const selectBodega = document.getElementById("select_bodega_producto");
    const selectMoneda = document.getElementById("select_moneda_precio_producto");
    const inputPrecio = document.getElementById("input_precio_producto");
    const checkboxesMateriales = Array.from(document.getElementsByName("checkbox_materiales"));
    const selectSucursal = document.getElementById("select_sucursal_bodega_producto");
    const textAreaDescripcion = document.getElementById("textarea_descripcion_producto");

    //Carga de datos
    const listadoBodegas = await obtenerBodegas();
    if(listadoBodegas) {
        establecerOpcionesCampoBodega(listadoBodegas, selectBodega);
    }

    const listadoMonedas = await obtenerMonedas();
    if(listadoMonedas) {
        establecerOpcionesCampoMoneda(listadoMonedas, selectMoneda);
    }

    //Enlazar función para gestionar el envío del formulario
    formularioCreacion.onsubmit = async e => {
        e.preventDefault();

        //Validaciones código producto
        const codigoEstaVacio = !inputCodigo.value;
        if (codigoEstaVacio) {
            alert("El código del producto no puede estar en blanco.");
            return;
        }

        const largoCodigoValido = validarLargoMinimoyMaximo(5, 15, inputCodigo.value);
        if (!largoCodigoValido) {
            alert("El código del producto debe tener entre 5 y 15 caracteres.");
            return;
        }

        const codigoTieneFormatoCorrecto = validarFormatoCodigoProducto(inputCodigo.value);
        if (!codigoTieneFormatoCorrecto) {
            alert("El código del producto debe contener letras y números");
            return;
        }

        const mensajeValidacionCodigoExistente = await verificarCodigoProductoExistente(inputCodigo.value);
        if (mensajeValidacionCodigoExistente) {
            alert(mensajeValidacionCodigoExistente);
            return;
        }

        //Validaciones nombre producto
        const nombreEstaVacio = !inputNombre.value;
        if (nombreEstaVacio) {
            alert("El nombre del producto no puede estar en blanco.");
            return;
        }

        const largoNombreValido = validarLargoMinimoyMaximo(2, 50, inputNombre.value);
        if (!largoNombreValido) {
            alert("El nombre del producto debe tener entre 2 y 50 caracteres.");
            return;
        }

        //Validaciones bodega producto
        const seleccionoBodega = validarElementoSelectIndicaUnaOpcion(selectBodega);
        if (!seleccionoBodega) {
            alert("Debe seleccionar una bodega.");
            return;
        }

        //Validaciones sucursal bodega producto
        const seleccionoSucursal = validarElementoSelectIndicaUnaOpcion(selectSucursal);
        if (!seleccionoSucursal) {
            alert("Debe seleccionar una sucursal para la bodega seleccionada.");
            return;
        }

        //Validaciones moneda producto
        const seleccionoMoneda = validarElementoSelectIndicaUnaOpcion(selectMoneda);
        if (!seleccionoMoneda) {
            alert("Debe seleccionar una moneda para el producto.");
            return;
        }

        //Validaciones precio producto
        const precioEstaVacio = !inputPrecio.value;
        if (precioEstaVacio) {
            alert("El precio del producto no puede estar en blanco.");
            return;
        }

        const precioTieneFormatoCorrecto = validarFormatoPrecioProducto(inputPrecio.value);
        if (!precioTieneFormatoCorrecto) {
            alert("El precio del producto debe ser un número positivo con hasta dos decimales.");
            return;
        }

        //Validaciones materiales producto
        const seleccionoAlMenosDosMateriales = validarMinimoMaterialesSeleccionados(checkboxesMateriales);
        if (!seleccionoAlMenosDosMateriales) {
            alert("Debe seleccionar al menos dos materiales para el producto.");
            return;
        }

        //Validaciones descripción producto
        const descripcionEstaVacio = !textAreaDescripcion.value;
        if (descripcionEstaVacio) {
            alert("La descripción del producto no puede estar en blanco.");
            return;
        }

        const largoDescripcionValido = validarLargoMinimoyMaximo(10, 1000, textAreaDescripcion.value);
        if (!largoDescripcionValido) {
            alert("La descripción del producto debe tener entre 10 y 1000 caracteres.");
            return;
        }

        //Al utilizar async/await se podra asignar el valor de devuelto por una funcion async sin tener que utilizar encadenamiento .then()
        const productoFueCreado = await enviarFormularioProducto(
            inputCodigo.value,
            inputNombre.value,
            parseInt(selectBodega.value),
            //Crea un nuevo arreglo con las opciones marcadas
            checkboxesMateriales.map(checkbox => {
                if(checkbox.checked) {
                    return checkbox.id;
                } else {
                    return null;
                }
            }).filter(x => x != null),
            parseInt(selectSucursal.value),
            parseInt(selectMoneda.value),
            inputPrecio.value,
            textAreaDescripcion.value
        );

        //Se restablecera el formulario en caso de que se haya guardado con éxito
        if(productoFueCreado) {
            e.target.reset();
        }
    };

    //Enlazar función para gestionar el cambio de bodega
    selectBodega.onchange = async e => {
        const idBodegaSeleccionada = selectBodega.selectedOptions[0].value;
        const listadoSucursales = await obtenerSucursalesSegunBodega(idBodegaSeleccionada);
        if(listadoSucursales) {
            establecerOpcionesCampoSucursal(listadoSucursales, selectSucursal);
        }
    };
});

//Validaciones
function validarElementoSelectIndicaUnaOpcion(elementoSelect) {
    const opcionSeleccionada = elementoSelect.selectedOptions[0];
    debugger;
    if(opcionSeleccionada.value === "-1") {
        return false;
    }
    return true;
}
function validarMinimoMaterialesSeleccionados(elementosCheckbox) {
    let cantidadMaterialesSeleccionados = 0;
    debugger;
    elementosCheckbox.forEach(checkbox => {
        debugger;
        if(checkbox.checked) {
            cantidadMaterialesSeleccionados++;
            debugger;
        }
    })

    if(cantidadMaterialesSeleccionados >= 2) {
        debugger;
        return true;
    }
    debugger;
    return false;
}
function validarLargoMinimoyMaximo(min, max, valor) {
    const porDebajoDelLargoMinimo = valor.length < min;
    const excedeLargoMaximo = valor.length > max;
    if(porDebajoDelLargoMinimo || excedeLargoMaximo) {
        return false;
    }
    return true;
}
function validarFormatoPrecioProducto(valor) {
    //Encuentra exclusivamente valores 0
    //Considera un posible separador "."
    const regexSoloValoresCeros = new RegExp(/^0*[.]?0*$/);

    //Si encuentra algún dígito distinto a 0, no devolvera resultados
    const esNumeroPositivo = valor.match(regexSoloValoresCeros) == null;


    //Comprueba que inicialmente hayan dígitos N veces, posiblemente seguido por un separador
    //Solo verificara los decimales si es que el separador esta presente
    //Considera uno o dos dígitos despues del separador
    const regexContieneDosDecimales = new RegExp(/^\d*[.]?(?=\d)\d{0,2}$/);

    //Fallara si excede dos dígitos despues del separador o no se incluyen dígitos despues de éste
    const contieneDecimalesValidos = valor.match(regexContieneDosDecimales) != null;

    debugger;
    if(esNumeroPositivo && contieneDecimalesValidos) {
        return true;
    }
    return false;
}
function validarFormatoCodigoProducto(valor) {
    //Que sean letras desde la "a" hasta la "z"
    //No distingue entre mayusculas o minusculas.
    //Devuelve inmediatamente despues de hacer un match.
    const regexEncontrarLetras = new RegExp(/[a-z]/i);

    //Que sean dígitos entre el 0 al 9
    //Devuelve inmediatamente despues de hacer un match.
    const regexEncontrarDigitos = new RegExp(/[0-9]/);

    //Que no sean letras ni dígitos, utilizando ^ como negación
    //No distingue entre mayusculas o minusculas para letras.
    //Devuelve inmediatamente despues de hacer un match.
    const regexEncontrarCaracteresEspeciales = new RegExp(/[^a-z0-9]/i);

    const contieneLetras = valor.match(regexEncontrarLetras) != null;
    const contieneDigitos = valor.match(regexEncontrarDigitos) != null;
    const contieneCaracteresEspeciales = valor.match(regexEncontrarCaracteresEspeciales) != null ;

    if(contieneLetras && contieneDigitos && !contieneCaracteresEspeciales) {
        return true;
    }
    return false;
}

//Utilidades
function limpiarOpcionesElementoSelect(elementoSelect) {
    //Crear un arreglo a partir de los descendientes del elemento select, y si no es la primera opción, quitarlo
    Array.from(elementoSelect.children).forEach((opcionSelect, indice) => {
        if(indice !== 0) {
            opcionSelect.remove();
        }
    });
}
function establecerOpcionesCampoBodega(bodegas, elementoSelectBodega) {
    limpiarOpcionesElementoSelect(elementoSelectBodega);

    bodegas.forEach(bodega => {
        const opcion = document.createElement("option");
        opcion.text = bodega.nombre;
        opcion.value = bodega.idBodega;

        elementoSelectBodega.append(opcion);
        elementoSelectBodega.selectedIndex = 0;
    });
}
function establecerOpcionesCampoMoneda(monedas, elementoSelectMoneda) {
    limpiarOpcionesElementoSelect(elementoSelectMoneda);

    monedas.forEach(moneda => {
        const opcion = document.createElement("option");
        opcion.text = moneda.nombre;
        opcion.value = moneda.idMoneda;

        elementoSelectMoneda.append(opcion);
        elementoSelectMoneda.selectedIndex = 0;
    });
}
function establecerOpcionesCampoSucursal(sucursales, elementoSelectSucursal) {
    limpiarOpcionesElementoSelect(elementoSelectSucursal);

    sucursales.forEach(sucursal => {
        const opcion = document.createElement("option");
        opcion.text = sucursal.nombre;
        opcion.value = sucursal.idSucursal;

        elementoSelectSucursal.append(opcion);
        elementoSelectSucursal.selectedIndex = 0;
    });
}

//Fetch
//En caso de que el servidor no fuese a responder una solicitud u otro error durante esta
const MENSAJE_ERROR_SOLICITUD_POR_DEFETO = "No pudimos procesar tu solicitud. Por favor intentelo más tarde."

const URL_LISTADO_BODEGAS = "http://localhost/src/bodegas.php";
const URL_LISTADO_MONEDAS = "http://localhost/src/monedas.php";
const URL_SUCURSALES_SEGUN_BODEGA = "http://localhost/src/sucursales-segun-bodega.php";
const URL_DISPONIBILIDAD_CODIGO_PRODUCTO = "http://localhost/src/disponibilidad-codigo-producto.php";
const URL_CREACION_PRODUCTO = "http://localhost/src/crear-producto.php";
async function verificarCodigoProductoExistente(codigoProducto) {
    try {
        const urlConParametros = `${URL_DISPONIBILIDAD_CODIGO_PRODUCTO}?codigoProducto=${encodeURIComponent(codigoProducto)}`;
        const respuesta = await fetch(urlConParametros, { method: "GET" });
        const datosRespuesta = await respuesta.json();
        if(!respuesta.ok) {
            return datosRespuesta.mensaje;
        }
        return "";
    } catch (error) {
        return MENSAJE_ERROR_SOLICITUD_POR_DEFETO
    }
}
async function obtenerSucursalesSegunBodega(idBodega) {
    try {
        const urlConParametros = `${URL_SUCURSALES_SEGUN_BODEGA}?idBodega=${idBodega}`;
        const respuesta = await fetch(urlConParametros, { method: "GET" });
        const { datos, mensaje } = await respuesta.json();
        if (!respuesta.ok) {
            alert(mensaje);
            return;
        }
        return datos;
    } catch (error) {
        alert(MENSAJE_ERROR_SOLICITUD_POR_DEFETO);
    }
}
async function obtenerBodegas() {
    try {
        const respuesta = await fetch(URL_LISTADO_BODEGAS, { method: "GET" });
        const { datos, mensaje } = await respuesta.json();
        if (!respuesta.ok) {
            alert(mensaje);
        } else if(respuesta.ok && datos.length === 0 && mensaje) {
            alert(mensaje);
        }
        return datos;
    } catch (error) {
        alert(MENSAJE_ERROR_SOLICITUD_POR_DEFETO);
    }
}
async function obtenerMonedas() {
    try {
        const respuesta = await fetch(URL_LISTADO_MONEDAS, { method: "GET" });
        const { datos, mensaje } = await respuesta.json();
        if (!respuesta.ok) {
            alert(mensaje);
            return;
        } else if(respuesta.ok && datos.length === 0 && mensaje) {
            alert(mensaje);
        }
        return datos;
    } catch (error) {
        alert(MENSAJE_ERROR_SOLICITUD_POR_DEFETO);
    }
}
async function enviarFormularioProducto(codigo, nombre, idBodega, materiales, idSucursal, idMoneda, precio, descripcion) {
    try {
        const respuesta = await fetch(URL_CREACION_PRODUCTO, {
            method: "POST",
            body: JSON.stringify({
                    codigo,
                    nombre,
                    idBodega,
                    materiales,
                    idSucursal,
                    idMoneda,
                    precio,
                    descripcion
                })
        });
        const datosRespuesta = await respuesta.json();
        if (!respuesta.ok) {
            alert(datosRespuesta.mensaje);
            return false;
        }

        alert(datosRespuesta.mensaje);
        return true;
    } catch (error) {
        alert(MENSAJE_ERROR_SOLICITUD_POR_DEFETO);
        return false;
    }
}