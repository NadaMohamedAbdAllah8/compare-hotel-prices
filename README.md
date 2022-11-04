# compare-hotel-prices

## Steps to run the project

1. Create the database. There is a sql script attached to create the database tables

2. For the first time, use composer dump-autload, in order to autolaod the classes. Note that you will need composer to be installed first before running the command.

3. Use the collection to run the APIs
   a.  Create advertiser
   b.  Run StoreData1FromAPI, or StoreData2FromAPI to store the API data to the database. Both will need the advertiser id as a parameter to run
   c.  Run ComparePrices to return a list of all the hotel rooms, without duplication, sorted from cheapest to expensive

Note: use StoreData1FromAPI to store data with the following structure
{
"hotels": [ {
"name": "Hotel A",
"stars": 4,
"rooms": [ {
"code": "DBL-TWN",
"net_price": "140.00",
"taxes": {
"amount": "12.00",
"currency": "EUR",
"type": "TAXESANDFEES"
},
"total": "152.00"
} ]
} ]
}

and use StoreData2FromAPI to store data with the following structure
{
"hotels": [ {
"name": "Hotel A",
"stars": 4,
"rooms": [ {
"code": "DBL-TWN",
"name": "Double or Twin SUPERIOR",
"net_rate": "143.00",
"taxes": [ {
"amount": "10.00",
"currency": "EUR",
"type": "TAXESANDFEES"
} ],
"totalPrice": "153.00"
} ]
} ]
}
