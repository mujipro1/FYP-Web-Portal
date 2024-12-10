const cropExpenseData = [
    {
        "head" : "Labour",
        "sub-head" :[
            'Sowing','Harvesting', 'Loading/Unloading','Fertilizer','Poison','Spray','Mulching','Cleaning','Driver','Food and Subsistence','Travel','Others' 
        ],
        'entry':[
            'No of Units',
            'Unit Expense (Per Acre)',
            'Total',
        ],
        'textbox':['Description']
    },

    {
        "head" : "Machinery",
        "sub-head" :[,'Rice Planter','Happy Seeder','Harvester Rent','Thresher Rent','Laser Level Rent','Corn Sowing Machine Rent','Silage Machine Rent','Transport','Others'
        ],
        'entry':[
            'No of Units',
            'Unit Expense (Per Acre)',
            'Total',
        ],
        'textbox':['Description']
    },

    {
        "head" : "Fertilizer",
        "sub-head" :[
           "DAP", "Nitrophas", "Zinc", "Homic Acid", "Urea", "Potash", "Sulphur", "Sulphur K2",'Single Super Phosphate',
           'NPK','Zinced Urea','Boric Acid','Ammonium Sulphate','Root Feed','Transport',"Others"
        ],
        'entry':[
            'Bag Weight (Kg)',
            'Unit Expense (Per Bag)',
            'Quantity',
            'Total',
        ],
        'textbox':['Description']
    },

    {
        "head" : "Seed",
        "sub-head" :[
            'Seed','Seedling','Transport','Others'
        ],
        'entry':[
            'Bag Weight (Kg)',
            'Unit Expense (Per Bag)',
            'Quantity',
            'Total',
        ],
        'textbox':['Description']
    },

    {
        "head" : "Fuel",
        "sub-head" :['Tractor Diesel','Rice Planter Petrol',
            'Motorcycle Petrol' , ,'Tube well Peter Engine','Transport','Others'
        ],
        'entry':[
            'Quantity (Litres)',
            'Per Litre Price',
            'Total',
        ],
        'dropdown':[
            {'Purpose':['Land Preperation', 'Laser Level', 'Others']}
        ],
        'textbox':['Description']
    },

    {
        "head" : "Electricity Bills ",
        'entry':[
            'Month',
            'Amount'
        ],
    },

    {
        "head" : "Pesticides",
        "sub-head" :['Fungicide ','Pesticide','Weedicide','Poison ','Strengthen','Transport','Others'
        ],
        'entry':[
            'Bag Weight (Kg)',
            'Unit Expense (Per Bag)',
            'Quantity',
            'Total',
        ],
        'textbox':['Description']
    },

    {
        "head" : "Travels (Labor) ",
        'entry':[
           'Amount'
        ],
        'textbox':['Description']
    },
    
    {
        "head" : "Equipment",
        'entry':[
            'Amount'
        ],
        'textbox':['Description']
    },
    {
        "head" : "Miscellaneous",
        "sub-head" : ['Sacks and sew','Transport','Others'
        ],
        'entry':[
            'Amount'
        ],
        'textbox':['Description']
    },
];


