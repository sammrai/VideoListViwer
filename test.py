import time,os
import json
import signal
print "#"
def check_pid(pid):        
    try:
        os.kill(pid, 0)
    except OSError:
        return False
    else:
        return True

def ascii_encode_dict(data):
    ascii_encode = lambda x: x.encode('ascii') if isinstance(x, unicode) else x
    return dict(map(ascii_encode, pair) for pair in data.items())

def kill_process(file_name,pid):
	pid=int(pid)
	remove_pid(file_name,pid)
	if check_pid(pid):
		os.kill(pid, signal.SIGKILL)
		return 0
	else:
		return 1

def load_json(file_name):
	try:
		with open(file_name) as infile:
			j = json.load(infile, object_hook=ascii_encode_dict)
	except:
		j={}
	return j

def write_json(file_name,dic):
	with open(file_name, 'w') as f:
	    json.dump(dic, f)

def refresh(file_name):
	j = load_json(file_name)
	for i in j:
		remove_pid(file_name,i)


def add_pid(file_name,pid_dic):
	refresh(file_name)
	
	j=load_json(file_name)
	j.update(pid_dic)
	write_json(file_name,j)
	return j


def remove_pid(file_name,pid,force=False):
	pid=int(pid)
	if (os.path.exists(file_name) and not check_pid(pid)) or force:
		j=load_json(file_name)
		del j["%s"%pid]
		write_json(file_name,j)
		# print "#"


if __name__ == '__main__':
	pid =os.getpid()
	from datetime import datetime
	date = datetime.now().strftime("%Y/%m/%d %H:%M:%S")

	pid_dic = {pid:{"flag" : True,"time":date}}
	file_name="jobpid"

	add_pid(file_name,pid_dic)
	for i in range(6):
		print i
		# f=open("test_%03d"%i,"w")
		# f.close()
		# print os.system("touch %03d"%i)
		time.sleep(10)

	remove_pid(file_name,pid,force=True)
