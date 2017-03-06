#Replacement Merchandise Authorization Tool

This tool is to help decrease the pain point of processing a replacement merchandise authorization for a customer service representative at iSmartAlarm. This tool is meant to increase employee happiness and maintain customer satisfaction.

#####The existing 6-step process:

1. Ask for required replacement information (full name, shipping address, phone number, where product was purchased, product to replace, quantity, zendesk ticket #, reason for replacement)
2. In the internal RMA shared Excel, enter the information as 1 row, and create a new RMA ID #
3. Send customer an RMA sheet to include in return package. This sheet contains the unique RMA ID #
4. Create and print an internal Sample or Replacement sheet, containing replacement info, hand-signed by CEO/COO
5. File this sheet as a "sample" or "replacement" in either shared Excel file. Assign unique Sample/Replacement ID
6. Send this signed sheet to warehouse to ship replacement product.

#####The problems with this process:

- Tedious, complex process for customer service representative. Prone to errors.
- No form validation, resulting in inaccurate/nonexistent shipping addresses
- Manual implementation of RMA ID #'s or Sample/Replacement ID #'s results in duplicate/wrong ID formats
- Same information needs to be re-entered multiple times across multiple files - prone to mismatched information for the same RMA.
- Tedious, error-prone process results in decrease in both employee happiness and customer satisfaction. 

#####This tool helps by reducing process down to just ***3 steps***:

1. Ask for required replacement information (shipping address is validated upon input)
2. Generate external RMA sheet, and internal Sample/Replacement sheet with 1-click. CEO/COO digitally verifies and checks off RMA request.
3. Print and send sheet for warehouse to ship replacement product.

#####This tool solves all of the original problems above by:
- submitting replacement information into a centralized relational database (MySQL)
- validating form information before it reaches the warehouse - preventing significant delay
- simplifying RMA-filing process

### Things to Do

#####Priority
- ~~add RMA # function~~
- ~~add reference # function~~
- ~~allow RMA and references #'s to be posted~~
- ~~post everything via ajax~~
- ~~allow devices to be posted~~
- ~~set date_received through editablegrid~~
- add sample type dropdown field, customers or media/retailers
- ~~get DELETE working~~
- ~~allow updating for all tables~~
- print page
- ~~display RMA # in Early-Ships table~~
- ~~display RMA in returns and replacements~~
- change white font when updating cell
- check if customer is in early_ship table before inserting into DB
- validate form data client-side 
- validate form data server-side
- ~~GET devices from db for product list~~
- ~~make a device_requests table~~
- ~~get device_requests table working~~
- ~~edit delete query to not delete table~~
- ~~edit all queries to only select rows where deleted = 0~~
- pop-up enter reason for deletion
- ~~figure how to allow update devices. maybe use modal and existing javascript product CRUD build?~~
- ~~grab devices from requested_devices table by sample_id~~
- prevent modal from popping up for early_ship table

#####Nice to have

- remove time from date?
- re-organize website nav and layout
- fix gap produced by product list element
- tracking page
- validate address with google maps API
- figure how to write tests so I don't break stuff when refactoring
- refactor. lots of repetition in PHP for inserting data
- clean up and split javascript into multiple files. make a separate validators file
- change ALL server connections from mysqli to PDO
- login system
- give certain members permission to sign document
- figure how to store and display scanned/signed papers
- figure how to display customer address in a neater way in editablegrid
- redo this entire thing in React/Express, jQuery spaghetti code is becoming unmanageable
