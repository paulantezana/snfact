<div class="SnModal-wrapper" data-modal="categoryModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="categoryModalForm">
            <i class="icon-cross"></i>
        </div>
        <div class="SnModal-header"><i class="icon-file-plus SnMr-2"></i> Categoria</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" id="categoryForm" onsubmit="CategorySubmit(event)">
                <input type="hidden" class="SnForm-control" id="categoryId">
                <div class="SnForm-item required">
                    <label for="categoryName" class="SnForm-label">Categoria</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-package SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="categoryName" placeholder="Nombre de la categoría">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="categoryDescription" class="SnForm-label"> Descripción</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-file-text2 SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="categoryDescription">
                    </div>
                </div>
                <div class="SnForm-item">
                    <div class="SnSwitch">
                        <input class="SnSwitch-input" type="checkbox" id="categoryState">
                        <label class="SnSwitch-label" for="categoryState">Estado</label>
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block" id="categoryFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>
