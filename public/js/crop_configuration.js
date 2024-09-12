document.addEventListener("DOMContentLoaded", () => {
    const initialValues = {}; // Define initial values as per your requirements
    const deras  = derasData;
    let data = {
        ...initialValues,
        crops: [],
    };

    const popularCrops = ['Wheat','Sugarcane', 'Rice', 'Cotton', 'Maize']

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
    const saveCropButton = document.getElementById("saveCropButton");
    

    let currentCrop = null;

    const renderSelectedCrops = () => {
        selectedCropsContainer.innerHTML = "";
        // if no crops are selected, hide the next button
        if (data.crops.length === 0) {
            document.getElementById("nextButton").classList.add("d-none");
        } else {
            document.getElementById("nextButton").classList.remove("d-none");
        }
        data.crops.forEach((crop, index) => {
            const cropDiv = document.createElement("div");
            cropDiv.classList.add("col-md-12", "my-2");
            cropDiv.innerHTML = `
                <div class="container">
                    <div class="selected-crop row">

                        
                        <div class="col-md-8 p-3">
                            <h4>${crop.name} ${crop.year}</h4>
                            

                            
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
                <span class="mx-3">${crop}</span>
                <button class="btn cross" type="button" data-name="${crop}">+</button>
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

        cropname = currentCrop.name;

        page1 = document.querySelector(".page1");
        page2 = document.querySelector(".page2");

        btnclose = document.querySelector(".btn-close");
        btnclose.addEventListener("click", () => {
            page1.classList.remove("d-none");
            page2.classList.add("d-none");

            if (data.crops.length === 0) {
                document.getElementById("nextButton").classList.add("d-none");
            }
        });

        page1.classList.add("d-none");
        page2.classList.remove("d-none");

        if (currentCrop === null) return;
        if (currentCrop.name == 'Sugarcane'){
            
            async function getSugarcaneData() {
                try {
                    const data = await fetchSugarcane();
                    if (data.length == 0) {
                        return;
                    }
                    document.getElementById('sugarcane').classList.remove('d-none');

                    sugarcanePreviousCrop = document.getElementById('sugarcanePreviousCrop');
                    data.forEach(sugarcane => {
                        let option = document.createElement('option');
                        option.value = sugarcane.id;
                        option.text = sugarcane['identifier'];
                        sugarcanePreviousCrop.appendChild(option);
                    });

                } catch (err) {
                    console.log(err); // Handle any errors here
                }
            }
            
            getSugarcaneData();
            
        }
        else{
            document.getElementById('sugarcane').classList.add('d-none');
            document.getElementById('sugarcanePreviousCrop').innerHTML = '';

        }
        
        cropAcresInput.value = "";
        derasContainer.innerHTML = `
        <hr class='my-4'>
        <p class='light fsmall mt-4' >Deras Information <span class='required'> *</span></p>`;
        cropNameModal.innerHTML = `<h4 id='cropname' class='my-3'>${currentCrop.name}</h4>`;

        if (deras.length > 0) {
            deras.forEach((dera, index) => {
                const deraDiv = document.createElement("div");
                deraDiv.classList.add("mb-3");
                deraDiv.innerHTML = `
                <div class="d-flex justify-content-between">
                <div class="form-check w-25">
                <input class="form-check-input form-check" type="checkbox" value="${dera}" id="dera${index}">
                <label class="form-check-label mt-1 form-check" for="dera${index}">${dera}</label>
                </div>
                <div class="mt-1 d-flex w-75">
                <label for="deraAcres${index}" class="mx-2 form-label">Acres</label>
                <input required type="number" min='0' class="form-control" id="deraAcres${index}" disabled>
                </div>
                </div>
                `;
                derasContainer.appendChild(deraDiv);
                hr = document.createElement('hr');
                hr.classList.add('my-5');
                derasContainer.appendChild(hr);

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

                deraAcresInput.addEventListener("keyup", (e) =>{
                    value = e.target.value;
                    e.target.value = Math.abs(value);
                });

            });
            cropAcresInput.parentElement.classList.add("d-none");
        } else {
            derasContainer.innerHTML = "";
            cropAcresInput.parentElement.classList.remove("d-none");
        }

        
        // cropModal.show();
    };

    saveCropButton.addEventListener("click", () => {

       

        const year = cropYearInput.value;
        
        const sowingDate = cropSowingDate.value;
        const harvestDate = cropHarvestDate.value;
        const desc = description.value;
        const variety = cropVariety.value;
        const polygonData = document.getElementById('polygonData').value;

        sameSeed = null;
        sugarcanePreviousCrop = null;
        if (currentCrop.name == 'Sugarcane'){
            sameSeed = document.getElementById('sameSeed').checked ? 1 : 0;
            sugarcanePreviousCrop = document.getElementById('sugarcanePreviousCrop').value;
        }


        let status;
            for (const statusX of cropStatus) {
                if (statusX.checked) {
                    status = statusX.value;
                    break;
                }
            }

        // check if all fields are present other than description

        if (!year || year === "") {
            showAlert("Please select a year.", "danger");
            return;
        }

        if (!sowingDate || sowingDate === "") {
            showAlert("Please enter a sowing date.", "danger");
            return;
        }


        let totalAcres = 0;
        const selectedDeras = [];

        deras.forEach((dera, index) => {
            const deraCheckbox = document.getElementById(`dera${index}`);
            const deraAcresInput = document.getElementById(`deraAcres${index}`);
            if (deraCheckbox.checked) {

                if (!deraAcresInput.value) {
                    showAlert("Please enter acres for all selected deras.", "danger");
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
            showAlert('Please select at least one Dera', 'danger');
            return;
        }

        if (deras.length === 0) {
            totalAcres = cropAcresInput.value;
            if (!totalAcres) {
                showAlert('Please select at least one Dera or enter acres if no Deras are present.', 'danger');
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
            desc,
            polygonData,
            sameSeed,
            sugarcanePreviousCrop
        };

        data.crops.push(cropData);
        page1 = document.querySelector(".page1");
        page2 = document.querySelector(".page2");

        page1.classList.remove("d-none");
        page2.classList.add("d-none");

        renderSelectedCrops();
    
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
            document.getElementById("nextButton").classList.add("d-none");
            showAlert('Please add at least one crop.', 'danger');
        }
    });

   
    renderSelectedCrops();
    renderPopularCrops();
});


function fetchSugarcane(){
    return fetch('/fetch-sugarcane/' + farm_id)
        .then(res => res.json())
        .then(data => {
            return data;
        })
        .catch(err => console.log(err));
}

