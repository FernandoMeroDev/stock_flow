class DragAndDropSystem {
    // Classes to add a hovered element
    hoverClasses = ['bg-blue-600', 'opacity-50'];

    enableDragAndDrop() {
        // Find the Main Livewire component
        const $wire = Livewire.getByName('warehouses.shelves.levels.edit.main')[0];

        // Unable Livewire actions to prevent livewire conflicts with HTML drag and drop
        this.disableActions($wire);

        // Declare Dragged Element variable
        let draggedElement = null;

        // Get Product Rows (TR elements)
        let productRows = document.querySelectorAll('.productRowDraggable');

        if(productRows.length < 2){
            alert('Debe haber al menos dos productos');
            return;
        }

        function addDragAndDropEvents(element, system) {
            element.addEventListener('dragstart', (event) => {
                draggedElement = element;
            });

            element.addEventListener('dragover', (event) => {
                event.preventDefault();
                system.changeClasses(element, system.hoverClasses, 'add');
            });

            element.addEventListener('dragleave', (event) => {
                system.changeClasses(element, system.hoverClasses, 'remove');
            });

            element.addEventListener('drop', (event) => {
                system.changeClasses(element, system.hoverClasses, 'remove');
                const targetElement = element;
                if (draggedElement !== targetElement) {
                    const container = targetElement.parentNode;
                    // Clone elements
                    const draggedCloned = draggedElement.cloneNode(true);
                    const targetCloned = targetElement.cloneNode(true);

                    // Replace elements for clones in inverse order
                    container.replaceChild(draggedCloned, targetElement);
                    container.replaceChild(targetCloned, draggedElement);

                    // Add Event Listeners to replaced elements
                    addDragAndDropEvents(draggedCloned, system);
                    addDragAndDropEvents(targetCloned, system);

                    // Send new order to server
                    let ordered_products = [];
                    document.querySelectorAll('.productRowDraggable').forEach((row, key) => {
                        ordered_products.push(row.id.substring(8));
                    });
                    $wire.reorderProducts(ordered_products);
                }
            });
        }

        // Add Event listeners to Product Rows
        productRows.forEach((row) => {
            row.draggable = true;
            addDragAndDropEvents(row, this);
        });
    }

    disableActions($wire) {
        document.querySelectorAll('.livewireActionButton').forEach(button => {
            button.style.display = 'none';
        });
        $wire.$dispatch('enabled-drag-and-drop');
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
        button.disabled = false;
        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.target.disabled = true;
            this.enableDragAndDrop();
        });
    }
}

( new DragAndDropSystem() ).init();