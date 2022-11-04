# compare-hotel-prices

## Steps to run the project

1. Create the database. There is a sql script attached to create the database tables

2. For the first time, use composer dump-autload, in order to autolaod the classes. Note that you will need composer to be installed first before running the command.

3. Use the collection to run the APIs
   a. Create advertiser
   b. Run StoreData1FromAPI, or StoreData2FromAPI to store the API data to the database
   c. Run ComparePrices to return a list of all the hotel rooms, without duplication, sorted from cheapest to expensive
