class DragAndDropSystem {
    // Main Livewire component
    $wire = null;

    // Current element being draggered
    draggedElement = null;

    // Classes to add a hovered element
    hoverClasses = ['bg-blue-600', 'opacity-50'];

    // Spacer
    // <tr class="border-b border-zinc-200 dark:border-zinc-700 last:border-0 text-black dark:text-white">
    //     <td class="h-4"></td>
    // </tr>
    spacerClasses = {
        trClasses: ['drag-and-drop-spacer', 'border-b', 'border-zinc-200', 'dark:border-zinc-700', 'last:border-0', 'text-black', 'dark:text-white'],
        tdClasses: ['h-4']
    };

    enableDragAndDrop() {
        // Find the Main Livewire component
        this.$wire = Livewire.getByName('warehouses.shelves.levels.edit.main')[0];

        // Unable Livewire actions to prevent livewire conflicts with HTML drag and drop API
        this.disableActions();

        // Get Product Rows (TR elements)
        let productRows = document.querySelectorAll('.productRowDraggable');

        if(productRows.length < 2){
            alert('Debe haber al menos dos productos');
            return;
        }

        // Add Event listeners and insert spacers to Product Rows
        productRows.forEach((row) => {
            row.draggable = true;
            this.addDragAndDropEvents(row);
        });
        this.insertSpacers(productRows);
    }

    disableActions() {
        document.querySelectorAll('.livewireActionButton').forEach(button => {
            button.style.display = 'none';
        });
        document.querySelectorAll('.open-product-edit-button').forEach(button => {
            button.disabled = true;
        });
        this.$wire.$dispatch('enabled-drag-and-drop');
    }

    addDragAndDropEvents(element) {
        element.addEventListener('dragstart', (event) => {
            this.draggedElement = element;
        });

        element.addEventListener('dragover', (event) => {
            event.preventDefault();
            this.changeClasses(element, this.hoverClasses, 'add');
        });

        element.addEventListener('dragleave', (event) => {
            this.changeClasses(element, this.hoverClasses, 'remove');
        });

        element.addEventListener('drop', (event) => {
            this.changeClasses(element, this.hoverClasses, 'remove');
            const targetElement = element;
            if (this.draggedElement !== targetElement) {
                const container = targetElement.parentNode;
                // Clone elements
                const draggedCloned = this.draggedElement.cloneNode(true);
                const targetCloned = targetElement.cloneNode(true);

                // Replace elements for clones in inverse order
                container.replaceChild(draggedCloned, targetElement);
                container.replaceChild(targetCloned, this.draggedElement);

                // Add Event Listeners to replaced elements
                this.addDragAndDropEvents(draggedCloned);
                this.addDragAndDropEvents(targetCloned);

                // Send new order to server
                let ordered_products = [];
                document.querySelectorAll('.productRowDraggable').forEach((row, key) => {
                    ordered_products.push(row.id.substring(8));
                });
                this.$wire.reorderProducts(ordered_products);
                this.insertSpacers();
            }
        });
    }

    insertSpacers(rows = null) {
        if( ! rows )
            rows = Array.from(document.querySelector('.productRowDraggable').parentElement.children);
        const parent = rows[0].parentElement;
        // Clear old spacers if them exists
        let spacer_deleted = false;
        for(let i = 0; i < rows.length; i++){
            let row = rows[i];
            if(row.classList.contains('drag-and-drop-spacer')){
                parent.removeChild(row);
                spacer_deleted = true;
            }
        };
        // Update array if spacers were deleted
        if(spacer_deleted)
            rows = Array.from(document.querySelector('.productRowDraggable').parentElement.children);
        // Add Event listeners and insert spacers to Rows
        let spacer = null;
        for(let i = 0; i <= rows.length; i++){
            spacer = this.createSpacer();
            if(i < rows.length){
                let row = rows[i];
                parent.insertBefore(spacer, row);
            } else
                parent.appendChild(spacer);
            this.addDragAndDropEvents(spacer);
        };
    }

    createSpacer() {
        const tr = document.createElement('TR');
        tr.draggable = true;
        this.changeClasses(tr, this.spacerClasses.trClasses, 'add');
        const td = document.createElement('TD');
        this.changeClasses(td, this.spacerClasses.tdClasses, 'add');
        tr.appendChild(td);
        return tr;
    }

    changeClasses(element, classes, operation) {
        for(let cssClass of classes){
            if(operation == 'add')
                element.classList.add(cssClass);
            else if(operation == 'remove')
                element.classList.remove(cssClass);
        }
    }

    init() {
        let button = document.getElementById('ableDragAndDropButton');
        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.target.disabled = true;
            this.enableDragAndDrop();
        });
    }
}

( new DragAndDropSystem() ).init();