# -*- coding: utf-8 -*-

import sqlite3
import os
import json


def mkcmd(tabele_name, data, autoid=False):
	try:data=data[0]
	except:raise(Exception("input data as list. \nactual: %s"%(type(data))))

	cmd=[]
	if autoid:
		cmd.append("id integer primary key autoincrement")
	for key,value in data.items():
		cmd_one = str(key)
		if type(value)==type(0):
			cmd_one+= " int"
		if type(value)==type(0.):
			cmd_one+= " float"
		if type(value)==type(u""):
			cmd_one+= " text"
		if type(value)==type([]):
			cmd_one+= " sqlist"

		cmd.append(cmd_one)
		resistration_cmd="create table if not exists %s ("%tabele_name + ", ".join(cmd) + ")"
		insert_cmd = "insert into" + " %s "%tabele_name +  "(%s)"%", ".join(data.keys()) + " values " + "(%s)"%",".join(["?"]*len(data))
		sort_index=data.keys()
	return  resistration_cmd,insert_cmd,sort_index

def register_data(tabele_name, database_name, data, autoid=True):
	sqlite3.register_adapter(list, lambda l: ';'.join([str(i) for i in l]))
	sqlite3.register_converter("sqlist", lambda s: [(i) for i in s.split(';')])
	con = sqlite3.connect(database_name,detect_types = sqlite3.PARSE_DECLTYPES)
	con.row_factory = sqlite3.Row

	create_table, insert_sql, sort=  mkcmd(tabele_name, data, autoid)
	con.execute(create_table)
	content = [j_one.values() for j_one in data]
	con.executemany(insert_sql,content)
	con.commit()

def load_data(tabele_name, database_name):
	con = sqlite3.connect(database_name,detect_types = sqlite3.PARSE_DECLTYPES)
	con.row_factory = sqlite3.Row
	cur = con.cursor()
	select_sql = 'select * from %s'%tabele_name
	cur.execute(select_sql)
	return cur.fetchall()

if __name__ == "__main__":
	dbname = 'database.db'
	os.remove(dbname)
	j=json.load(open("title.json"))
	register_data("titles", dbname, j, autoid=False)
	# print len(load_data("titles", dbname))


