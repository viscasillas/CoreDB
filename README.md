# CoreDB
A powerful database with restful apis

CoreDB is a document based database

# Manual

	** COMMAND FLAGS			** DATABASE - requires -p command flag
	********************			*************************************************************************************
	*  -h = help 				* db [database_here] create [specific_id *optional]= creates a new object and returns the id;
	*  -p = load program 			* db [database_here] read [object_id_here]  = returns all fields and values
	*  -s = settings 			* db [database_here] read [object_id_here] [field]  = returns specific and value field
						* db [database_here] update [object_id_here] [field] [value] = creates or updates a field with value
	** API SERVICES				* db [database_here] delete [object_id_here] = delete an object by its id
	* stop = disable APIs			* db [database_here] list = returns all objects in a database
	* start = allow APIs			* db [database_here] find [term] = returns a list of object\'s ids that batch the term		
	* status = check status 		* db [database_here] -A destroy = destroys database	
						* db [database_here] -A start = creates a new database
						* db [database_here] update [object_id_here] [field] [value] +i [indexname] = updates & add to index
						* db [database_here] +i [value_to_index] [index_database_object]
						* db -all = show all databases

	** AUTO-INDEXING
	************************************************************************************************
	1. add a field titled "_index_val" with the value of the field that you want auto-indexed.
	2. add a field titled "_index_obj" with the value of the object in which you are indexing.
	3. this will update the index with that value on any update to the value of the object.
	4. this must be done individually to each object for it to be auto-indexed.

	** OBJECT SCHEMAS / TEMPLATES
	************************************************************************************************
	1. you can create a template for an object to use on the create command.
	2. create an object in the "_templates" database with the title you want for your template/schema.
	3. add fields to that template object that match the fields and values you would like created automatically.
	4. now simply use "create -t [template_object_here]" instead of just "create". 

	tip**: to have auto-indexing work automatically add the auto_index files to your templates. =D
