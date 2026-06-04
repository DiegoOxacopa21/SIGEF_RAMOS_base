// main.js - Lógica compartida, especialmente simulador de proforma

document.addEventListener('DOMContentLoaded', function() {
    
    // Lógica Simulador de Proforma
    const simAtaud = document.getElementById('sim_ataud');
    const simCheckboxes = document.querySelectorAll('.proforma-checkbox');
    
    if (simAtaud || simCheckboxes.length > 0) {
        
        function calcularProforma() {
            let subtotal = 0;
            const listaResumen = document.getElementById('sim_resumen_lista');
            listaResumen.innerHTML = '';
            let hasItems = false;
            
            // Ataud
            if (simAtaud && simAtaud.value !== "0") {
                const opt = simAtaud.options[simAtaud.selectedIndex];
                const price = parseFloat(opt.dataset.price);
                const name = opt.dataset.name;
                subtotal += price;
                addItemToList(listaResumen, name, price);
                hasItems = true;
            }
            
            // Servicios y Adicionales
            simCheckboxes.forEach(chk => {
                if (chk.checked) {
                    const price = parseFloat(chk.dataset.price);
                    const name = chk.dataset.name;
                    subtotal += price;
                    addItemToList(listaResumen, name, price);
                    hasItems = true;
                }
            });
            
            if (!hasItems) {
                listaResumen.innerHTML = '<li class="list-group-item text-muted text-center small" id="sim_empty">No hay servicios seleccionados.</li>';
            }
            
            const igv = subtotal * 0.18;
            const total = subtotal + igv;
            
            // Actualizar DOM
            document.getElementById('sim_subtotal').innerText = 'S/ ' + subtotal.toFixed(2);
            document.getElementById('sim_igv').innerText = 'S/ ' + igv.toFixed(2);
            document.getElementById('sim_total').innerText = 'S/ ' + total.toFixed(2);
        }
        
        function addItemToList(ul, name, price) {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between p-2 small';
            li.innerHTML = `<span>${name}</span> <span class="text-muted">S/ ${price.toFixed(2)}</span>`;
            ul.appendChild(li);
        }
        
        if (simAtaud) simAtaud.addEventListener('change', calcularProforma);
        simCheckboxes.forEach(chk => chk.addEventListener('change', calcularProforma));
    }
});
