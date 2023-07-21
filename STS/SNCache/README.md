# Using sys_choice table

* element - the field name, eg., u_host_type, os, u_distribution_switch
* name - the table name, eg., cmdb_ci_server, cmdb_ci, cmdb_ci_service
* label - the label of the drop down
* value - the value used when selecting from the drop down
* sys_id - you should know what this is by now

~~~
SELECT element, name, label, value, sys_id
from sys_choice
where inactive = false
and name = 'cmdb_ci_server'
~~~

# using
