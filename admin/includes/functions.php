<?php
function get_product($conx, $field, $value)
{
    $product = new Product();
    if(isset($field))
    {
        if(!isset($value))
        {
            echo "Value for the field is not set";
            return $product;
        }
        $query = mysqli_query($conx, "
        SELECT
            product.*, country.name as country, category.category_name as category
        from
            product
        JOIN category ON product.product_category=category.category_id
        JOIN country ON product.product_country=country.id
        WHERE
            product.$field='$value' LIMIT 1");
        if($query && mysqli_num_rows($query)>0)
        {
            $product_data = mysqli_fetch_array($query);
            $product->set_data($conx, $product_data);
        }
    }
    return $product;
}

function get_category($conx, $parent="NULL")
{

    if($parent=="NULL")
    {
        $query = "SELECT * FROM category WHERE parent is NULL";
    }else
    {
        $query = "SELECT * FROM category WHERE parent='$parent'";
    }

    $query = mysqli_query($conx, $query);

    if($query)
    {
?>
<ul class="categories_container">
    <?php
        while($row = mysqli_fetch_array($query))
        {
            extract($row);
            $is_parent = is_parent($conx, $category_id)
    ?>
    <li class="category_container">
        <div class="category">
            <?php
                if($is_parent)
                {
            ?>
            <span class="child-toggle">
                <span class="caret"></span>
            </span>
            <?php   }?>
            <a href="categories.php?catid=<?php echo $category_id;?>">
                <?php
            echo $category_name;
                ?>
            </a>
        </div>
        <?php
            if($is_parent)
            {
        ?>
        <div class="category_childs">
            <?php get_category($conx, $category_id);?>
        </div>
        <?php 
            }
        ?>
    </li>
    <?php
        }
    ?>
</ul>
<?php
    }
}

function get_category_select_options($conx, $selected=null, $level=0, $parent="NULL")
{
    if($parent=="NULL")
    {
        $query = "SELECT * FROM category WHERE parent is NULL";
    }else
    {
        $query = "SELECT * FROM category WHERE parent='$parent'";
    }

    $query = mysqli_query($conx, $query);

    if($query)
    {
?>
<?php
        while($row = mysqli_fetch_array($query))
        {
            extract($row);
            $is_parent = is_parent($conx, $category_id);
?>
<option <?php
            if($selected!=null)
            {
                if($selected==$category_id)
                {
                    echo "selected";
                }
            }
        ?> value="<?php echo $category_id;?>">
    <?php
            echo str_repeat(" &nbsp; &nbsp; ", $level);
            echo $level>0?">":"";
            echo " $category_name";
    ?>
</option>
<?php
            if($is_parent)
            {
                get_category_select_options($conx, $selected, $level+1, $category_id);
            }

        }
    }
}

function get_country_select_options($conx, $country_id=null)
{
    $query = "SELECT * FROM country WHERE status='1'";
    $result = mysqli_query($conx, $query);
    if($result && mysqli_num_rows($result)>0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $id = $row["id"];
            $name = $row["name"];
            ?>
            <option value="<?=$id?>" <?php if($country_id != null)
            {
                if($country_id == $id)
                {
                    echo "selected";
                }
            }?> ><?=$name?></option>
            <?php
        }
    }else
    {
        echo "No country";
    }
}

function get_category_path($conx, $item)
{
    $result = "";
    $query = mysqli_query($conx, "  SELECT
                                        *
                                    FROM
                                        category
                                    WHERE
                                        category_id='$item'
                                        LIMIT 1
                                  ");
    if($query)
    {
        while($row = mysqli_fetch_array($query))
        {
            $parent = $row["parent"];
            $name = $row["category_name"];
            $id = $row["category_id"];
            if($parent)
            {
                $result .= get_category_path($conx, $parent);
            }
            $result .= " / <a href=\"categories.php?catid=$id\">$name</a>";
        }
    }
    return $result;
}


function get_products_list($conx)
{
    $query = 'SELECT
        product.*, country.name as country, category.category_name as category
    from
        product
        JOIN category ON product.product_category=category.category_id
        JOIN country ON (product.product_country=country.id)
        ';
    $result = mysqli_query($conx, $query);
    $products = array();
    if($result)
    {
        if(mysqli_num_rows($result)<1)
        {

        }else
        {
            while($row = mysqli_fetch_array($result))
            {

                $products[] = new Product($conx, $row);
            }
        }
    }

    return array("products"=>$products, "I"=>0);
}

function fetch_products(&$products)
{
    if(isset($products["products"][($products["I"])]))
    {
        return $products["products"][($products["I"]++)];
    }
}

function get_field_by_id($conx, $table, $field_to_return, $field, $id)
{
    $query = mysqli_query($conx, "SELECT * FROM $table WHERE $field='$id'");
    $row = mysqli_fetch_array($query);
    return $row[$field_to_return];
}



?>