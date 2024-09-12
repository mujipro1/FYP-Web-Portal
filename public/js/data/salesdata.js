const salesData = {
    "Wheat": [
        { "label": "Date", "type": "text" },
        { "label": "No of Acres to Sell", "type": "num" },
        { "label": "Dera", "type": "text" },
        { "label": "No. of Bags", "type": "num" },
        { "label": "Weight Per Bag", "type": "num", "id": "weightPerBag" },
        { "label": "Weight (KGs)", "type": "num", "id": "totalWeight" },
        { "label": "Weight (Muns)", "type": "num", "readonly": true ,'id':'weightMuns' },
        { "label": "Selling Category", "type": "dropdown", "options": ["Govt", "Charity", "Seed", "Eating", "Private", "Hand Cutt Labor", "Thresher Labor", "Other"] },
        { "label": "Sales Type", "type": "text" },
        { "label": "Unit Price", "type": "num", "id": "unitPrice" },
        { "label": "Amount", "type": "num", "id": "amount", "readonly": true },
        { "label": "Remarks", "type": "text" }
    ],
    "All": [
        { "label": "Date", "type": "text" },
        { "label": "No of Acres to Sell", "type": "num" },
        { "label": "Dera", "type": "text" },
        { "label": "Weight (KGs)", "type": "num", "id": "totalWeightAll" },
        { "label": "Weight (Muns)", "type": "num", "readonly": true ,'id':'weightMuns'  },
        { "label": "Unit Price", "type": "num", "id": "unitPriceAll" },
        { "label": "Amount", "type": "num", "id": "amountAll", "readonly": true },
        { "label": "Remarks", "type": "text" }
    ],
    "Rice": [
        { "label": "Date", "type": "text" },
        { "label": "Variety", "type": "text" },
        { "label": "Dera", "type": "text" },
        { "label": "No of Acres to sell", "type": "num" },
        { "label": "No. of Bags", "type": "num" },
        { "label": "Weight Per Bag", "type": "num", "id": "weightPerBagRice" },
        { "label": "Weight (KGs)", "type": "num", "id": "totalWeightRice" },
        { "label": "Weight (Muns)", "type": "num" , "readonly": true,'id':'weightMuns'  },
        { "label": "Unit Price", "type": "num", "id": "unitPriceRice" },
        { "label": "Amount", "type": "num", "id": "amountRice", "readonly": true },
        { "label": "Discount", "type": "num", "id": "discountRice" },
        { "label": "In Account", "type": "num", "readonly": true, "id": "inAccountRice" },
        { "label": "In Cash", "type": "num", "readonly": true, "id": "inCashRice" },
        { "label": "Remarks", "type": "text" }
    ],
    "Corn": [
        { "label": "Date", "type": "text" },
        { "label": "No of Acres to Sell", "type": "num" },
        { "label": "Dera", "type": "text" },
        { "label": "Weight (KGs)", "type": "num", "id": "totalWeightCorn" },
        { "label": "Weight (Muns)", "type": "num" , "readonly": true,'id':'weightMuns'  },
        { "label": "Unit Price", "type": "num", "id": "unitPriceCorn" },
        { "label": "Amount", "type": "num", "id": "amountCorn", "readonly": true },
        { "label": "In Account", "type": "num", "readonly": true, "id": "inAccountCorn" },
        { "label": "In Cash", "type": "num", "readonly": true, "id": "inCashCorn" },
        { "label": "Remarks", "type": "text" }
    ],
    "Sugarcane": [
        { "label": "Date", "type": "text" },
        { "label": "Dera", "type": "text" },
        { "label": "Mill", "type": "text" },
        { "label": "CPR No", "type": "text" },
        { "label": "Acres", "type": "num" },
        { "label": "Weight (KGs)", "type": "num", "id": "totalWeightSugarcane" },
        { "label": "Weight (Muns)", "type": "num" ,'id':'weightMuns' , "readonly": true},
        { "label": "Production", "type": "text" },
        { "label": "CPR Amount", "type": "num" },
        { "label": "Transport Rent", "type": "num" },
        { "label": "Per Unit Transport Rent", "type": "num" },
        { "label": "Labour", "type": "num" },
        { "label": "Per Unit Labour", "type": "num" },
        { "label": "Extra Expense", "type": "num" },
        { "label": "Total Sales Expense", "type": "num", "readonly": true, "id": "totalSalesExpenseSugarcane" },
        { "label": "Date Of Bank Credit", "type": "text" },
        { "label": "Bank Credited Amount", "type": "num", "readonly": true, "id": "bankCreditedAmountSugarcane" },
        { "label": "Remarks", "type": "text" }
    ]
};