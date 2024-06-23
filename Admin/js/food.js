function fetchFoodData(food_id) {
        if (food_id) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "get_food.php?food_id=" + food_id, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const foodData = JSON.parse(xhr.responseText);
                    document.getElementById("edit_food_category").value = foodData.food_category;
                    document.getElementById("edit_food_name").value = foodData.food_name;
                    document.getElementById("edit_food_price").value = foodData.food_price;
                    document.getElementById("edit_food_quantity").value = foodData.food_quantity;
                    document.getElementById("edit_food_desc").value = foodData.food_desc;
                }
            };
            xhr.send();
        } else {
            document.getElementById("edit_food_category").value = '';
            document.getElementById("edit_food_name").value = '';
            document.getElementById("edit_food_price").value = '';
            document.getElementById("edit_food_quantity").value = '';
            document.getElementById("edit_food_desc").value = '';
        }
    }