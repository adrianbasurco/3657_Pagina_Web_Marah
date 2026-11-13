async function cargarProductos() {
    const respuesta = await fetch('api/productos');
    const resultado = await respuesta.json();

    const contenedor = document.getElementById('lista-productos');
    contenedor.innerHTML = '';

    resultado.data.forEach(producto => {
        const hayStock = producto.stock > 0;

        contenedor.innerHTML += `
            <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                <img src="img/kefir-1litro.jpeg" alt="${producto.nombre}" class="h-36 w-full bg-brand-50 object-contain p-2">
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase text-brand-600">${producto.categoria}</p>
                    <h3 class="mt-1 font-semibold text-slate-900">${producto.nombre} ${producto.presentacion}</h3>
                    <p class="mt-2 text-lg font-bold text-slate-900">S/. ${producto.precio}</p>
                    ${hayStock
                        ? '<span class="mt-3 inline-block rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Con stock</span>'
                        : '<span class="mt-3 inline-block rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700">Sin stock</span>'}
                </div>
            </article>
        `;
    });
}

cargarProductos();