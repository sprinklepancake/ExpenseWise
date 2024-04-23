document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById("category");
    const otherCategoryGroup = document.getElementById("otherCategoryGroup");
    const otherCategoryInput = document.getElementById("otherCategory");

    categorySelect.addEventListener("change", function() {
        if (categorySelect.value === "Other") {
            otherCategoryGroup.style.display = "block";
            otherCategoryInput.required = true;
        } else {
            otherCategoryGroup.style.display = "none";
            otherCategoryInput.required = false;
        }
    });
});
