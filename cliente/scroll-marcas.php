    <div class="scroller">
        <div class="scroller__inner">
            <?php
            include "coneccionbd.php";
            $brands_query = "SELECT marImg FROM marca ORDER BY marNombre ASC";
            $brands_result = mysqli_query($con, $brands_query);
            while ($brand = mysqli_fetch_assoc($brands_result)) {
                echo "<img src='/paneladministrador/recursos/uploads/marca/{$brand['marImg']}' alt='Marca'>";
            }
            ?>
        </div>
    </div>
