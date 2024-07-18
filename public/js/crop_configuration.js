document.addEventListener("DOMContentLoaded", () => {
    const initialValues = {}; // Define initial values as per your requirements
    const deras  = derasData;
    let data = {
        ...initialValues,
        crops: [],
    };

    const popularCrops = crops.slice(0, 4);

    const selectedCropsContainer = document.getElementById("selectedCropsContainer");
    const popularCropsContainer = document.getElementById("popularCropsContainer");
    const cropDropdown = document.getElementById("cropDropdown");
    const dropdownBox = document.getElementById("dropdownBox");
    const searchTerm = document.getElementById("searchTerm");

    const cropModal = new bootstrap.Modal(document.getElementById("cropModal"));
    const cropYearInput = document.getElementById("cropYear");
    const cropStatus = document.querySelectorAll('input[name="cropStatus"]');
    const cropSowingDate = document.getElementById("cropSowingDate");
    const cropHarvestDate = document.getElementById("cropHarvestingDate");
    const description = document.getElementById("description");
    const derasContainer = document.getElementById("derasContainer");
    const cropVariety = document.getElementById('variety');
    const cropNameModal = document.getElementById("cropNameModal");
    const cropAcresInput = document.getElementById("cropAcres");
    const cropStage = document.getElementById("stage");
    const saveCropButton = document.getElementById("saveCropButton");
    const noDeraAlert = document.getElementById("noDeraAlert");

    let currentCrop = null;

    const renderSelectedCrops = () => {
        selectedCropsContainer.innerHTML = "";
        data.crops.forEach((crop, index) => {
            const cropDiv = document.createElement("div");
            cropDiv.classList.add("col-md-12", "my-2");
            cropDiv.innerHTML = `
                <div class="container">
                    <div class="selected-crop row">

                        
                        <div class="col-md-8 p-3">
                            <h4>${crop.name}</h4>
                            <div class="labelcontainer2">
                                <label for="cropYear${index}">Year</label>
                                <input hidden type="text" class="form-control" id="cropYear${index}" value="${crop.year}" disabled />
                                <label>${crop.year}</label>
                            </div>

                            
                            ${crop.deras.map(dera => `
                                <div class="labelcontainer2">
                                    <label for="${dera.name}${index}">${dera.name}</label>
                                    <input hidden type="text" class="form-control" id="${dera.name}${index}" value="${dera.acres}" disabled />
                                    <label>${dera.acres}</label>
                                </div>`).join('')}

                            <div class="labelcontainer2">
                                <label for="cropAcres${index}">Total Acres</label>
                                <input hidden type="text" class="form-control" id="cropAcres${index}" value="${crop.acres}" disabled />
                                <label>${crop.acres}</label>
                            </div>

                            <div class="labelcontainer2">
                                <label for="cropStatus${index}">Status</label>
                                <input hidden type="text" class="form-control" id="cropStatus${index}" value="${crop.status}" disabled />
                                <label>${crop.status == 1 ? "Active" : "Passive"}</label>
                            </div>

                            <div class="labelcontainer2">
                                <label for="cropSowingDate${index}">Sowing Date</label>
                                <input hidden type="text" class="form-control" id="cropSowingDate${index}" value="${crop.sowingDate}" disabled />
                                <label>${crop.sowingDate}</label>
                            </div>

                            <div class="labelcontainer2">
                                <label for="cropHarvestDate${index}">Harvest Date</label>
                                <input hidden type="text" class="form-control" id="cropHarvestDate${index}" value="${crop.harvestDate}" disabled />
                                <label>${crop.harvestDate}</label>
                            </div>

                            <div class="labelcontainer2">
                                <label for="variety" class="form-label w-50">Variety</label>
                                <input hidden type="text" class="form-control" id="cropVariety${index}" value="${crop.variety}" disabled />
                                <label>${crop.variety}</label>
                            </div>
                            
                            <div class=" labelcontainer2">
                                <label for='stage' class="form-label w-50">Stage</label>
                                <input hidden type="text" class="form-control" id="cropStage${index}" value="${crop.stage}" disabled />
                                <label>${crop.stage}</label>
                            </div>
                          
                        </div>

                        <div class="col-md-4">
                        
                            <div class='text-end'>
                                <button class="cross btn" type="button" data-index="${index}">&times;</button>
                            </div>
                            <img src="${crop.image}" alt="${crop.name}" class="crop-image" />
                        </div>
                      

                        
                    </div>
                </div>
            `;
            selectedCropsContainer.appendChild(cropDiv);
        });

        selectedCropsContainer.querySelectorAll(".cross").forEach((button) => {
            button.addEventListener("click", (e) => {
                const index = e.target.getAttribute("data-index");
                data.crops.splice(index, 1);
                renderSelectedCrops();
            });
        });
    };

    const renderPopularCrops = () => {
        popularCropsContainer.innerHTML = "";
        popularCrops.forEach((crop) => {
            const cropDiv = document.createElement("div");
            cropDiv.classList.add("popular-crop");
            cropDiv.innerHTML = `
                <span class="mx-3">${crop.name}</span>
                <button class="btn cross" type="button" data-name="${crop.name}">+</button>
            `;
            popularCropsContainer.appendChild(cropDiv);
        });

        popularCropsContainer.querySelectorAll(".cross").forEach((button) => {
            button.addEventListener("click", (e) => {
                const cropName = e.target.getAttribute("data-name");
                const crop = crops.find((c) => c.name === cropName);
                if (crop && !data.crops.some((c) => c.name === crop.name)) {
                    currentCrop = crop;
                    showCropModal();
                }
            });
        });
    };

    const handleDropdownSelect = (crop) => {
        if (!data.crops.some((c) => c.name === crop.name)) {
            currentCrop = crop;
            showCropModal();
        }
        searchTerm.value = "";
        dropdownBox.innerHTML = "";
        dropdownBox.hidden = true;
    };

    const handleSearchChange = (e) => {
        dropdownBox.hidden = false;
        if (e.target.value === "") {
            dropdownBox.innerHTML = "";
            dropdownBox.hidden = true;
            return;
        }
        const term = e.target.value.toLowerCase();
        const filteredCrops = crops.filter((crop) =>
            crop.name.toLowerCase().includes(term)
        );
        dropdownBox.innerHTML = "";
        if (filteredCrops.length > 0) {
            filteredCrops.slice(0, 10).forEach((crop) => {
                const cropDiv = document.createElement("div");
                cropDiv.classList.add("dd-item");
                cropDiv.innerHTML = `
                    <img src="${crop.image}" alt="${crop.name}" class="crop-image" />
                    <span class="mx-4">${crop.name}</span>
                `;
                cropDiv.addEventListener("click", () =>
                    handleDropdownSelect(crop)
                );
                dropdownBox.appendChild(cropDiv);
            });
            dropdownBox.hidden = false;
        } else {
            dropdownBox.hidden = true;
        }
    };

    const handleClickOutside = (event) => {
        if (!cropDropdown.contains(event.target)) {
            searchTerm.value = "";
        }
    };

    const showCropModal = () => {
        cropAcresInput.value = "";
        derasContainer.innerHTML = "";
        cropNameModal.innerHTML = `<h4>${currentCrop.name}</h4>`;

        if (deras.length > 0) {
            deras.forEach((dera, index) => {
                const deraDiv = document.createElement("div");
                deraDiv.classList.add("mb-3");
                deraDiv.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${dera}" id="dera${index}">
                        <label class="form-check-label" for="dera${index}">${dera}</label>
                    </div>
                    <div class="mb-3">
                        <label for="deraAcres${index}" class="form-label">Acres</label>
                        <input required type="number" class="form-control" id="deraAcres${index}" disabled>
                    </div>
                `;
                derasContainer.appendChild(deraDiv);

                const deraCheckbox = deraDiv.querySelector(`#dera${index}`);
                const deraAcresInput = deraDiv.querySelector(`#deraAcres${index}`);

                deraCheckbox.addEventListener("change", (e) => {
                    if (deraCheckbox.checked) {
                        deraAcresInput.disabled = false;
                    } else {
                        deraAcresInput.disabled = true;
                        deraAcresInput.value = "";
                    }
                });
            });
            cropAcresInput.parentElement.classList.add("d-none");
        } else {
            derasContainer.innerHTML = "";
            cropAcresInput.parentElement.classList.remove("d-none");
        }

        noDeraAlert.classList.add("d-none");
        cropModal.show();
    };

    saveCropButton.addEventListener("click", () => {
        const year = cropYearInput.value;
        
        const sowingDate = cropSowingDate.value;
        const harvestDate = cropHarvestDate.value;
        const desc = description.value;
        const variety = cropVariety.value;
        const stage = cropStage.value;

        let status;
            for (const statusX of cropStatus) {
                if (statusX.checked) {
                    status = statusX.value;
                    break;
                }
            }

        // check if all fields are present other than description
        if (!sowingDate || sowingDate === "") {
            alert("Please enter a sowing date.");
            return;
        }

        if (!harvestDate || harvestDate === "") {
            alert("Please enter a harvest date.");
            return;
        }


        if (!year || year === "") {
            alert("Please enter a year.");
            return;
        }

        let totalAcres = 0;
        const selectedDeras = [];

        deras.forEach((dera, index) => {
            const deraCheckbox = document.getElementById(`dera${index}`);
            const deraAcresInput = document.getElementById(`deraAcres${index}`);
            if (deraCheckbox.checked) {

                if (!deraAcresInput.value) {
                    alert("Please enter acres for all selected deras.");
                    return;
                }

                selectedDeras.push({
                    name: dera,
                    acres: deraAcresInput.value,
                });
                totalAcres += parseFloat(deraAcresInput.value) || 0;
            }
            else{
                return;
            }
        });

        if (deras.length > 0 && selectedDeras.length === 0) {
            noDeraAlert.classList.remove("d-none");
            return;
        }

        if (deras.length === 0) {
            totalAcres = cropAcresInput.value;
            if (!totalAcres) {
                noDeraAlert.classList.remove("d-none");
                return;
            }
        }

        const cropData = {
            ...currentCrop,
            name: `${currentCrop.name} `, // Update crop name with year
            year,
            acres: totalAcres,
            deras: selectedDeras,
            status,
            sowingDate,
            harvestDate,
            variety,
            stage,
            desc
        };

        data.crops.push(cropData);
        renderSelectedCrops();
        cropModal.hide();
    });

    document.addEventListener("mousedown", handleClickOutside);
    searchTerm.addEventListener("input", handleSearchChange);
    // add focus event listener to the search term
  
    searchTerm.addEventListener("blur", () => {
        // check if there is something in the search term
        // if not, hide the dropdown
        if (searchTerm.value === "") {
            dropdownBox.innerHTML = "";
            dropdownBox.hidden = true;
        }
    });

    document.getElementById("nextButton").addEventListener("click", (e) => {
        e.preventDefault();
        if (data.crops.length > 0) {

            // create input 
            let input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "cropDetails");
            input.setAttribute("value", JSON.stringify(data.crops));
            document.getElementById("cropDetailsForm").appendChild(input);

            document.getElementById("cropDetailsForm").submit();

        } else {
            alert("Please add at least one crop.");
        }
    });

   
    renderSelectedCrops();
    renderPopularCrops();
});
