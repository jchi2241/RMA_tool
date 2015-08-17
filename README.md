# Things to Do

####Priority
- check if customer email exists, pre-fill fields if it's is already in DB.
- convert everything from mysqli to PDO
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
- if sample formType is not selected, hide early_ship option
- if sample is not for an RMA, 

####Nice to have

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

- put this in a CMS, drupal?