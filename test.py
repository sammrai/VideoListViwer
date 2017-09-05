import time,os
import json
import signal
from datetime import datetime

class jobmng(object):
	"""docstring for ClassName"""
	def __init__(self,file_name):
		self.file_name=file_name
		self.reflesh()
				
	def pid_running_win(self, pid):
	    import ctypes
	    kernel32 = ctypes.windll.kernel32
	    SYNCHRONIZE = 0x100000

	    process = kernel32.OpenProcess(SYNCHRONIZE, 0, pid)
	    if process != 0:
	        kernel32.CloseHandle(process)
	        return True
	    else:
	        return False

	def pid_running_unix(self, pid):
	    try:
	        os.kill(pid, 0)
	    except OSError:
	        return False
	    else:
	        return True

	def pid_exists(self, pid):        
	    if os.name=="nt":return self.pid_running_win(pid)
	    if os.name=="posix":return self.pid_running_unix(pid)

	def ascii_encode_dict(self, data):
	    ascii_encode = lambda x: x.encode('ascii') if isinstance(x, unicode) else x
	    return dict(map(ascii_encode, pair) for pair in data.items())


	def kill_process_win(self, pid):
		if pid_exists(pid):
			os.kill(pid, signal.SIGTERM)
			return 0
		else:
			return 1

	def kill_process_unix(self, pid):
		if pid_exists(pid):
			os.kill(pid, signal.SIGKILL)
			return 0
		else:
			return 1

	def kill_process(self, pid):
		pid=int(pid)
		self.remove_pid(self.file_name,pid,force=True)
		if os.name=="nt":return kill_process_win(pid)
		if os.name=="posix":return kill_process_unix(pid)


	def load_json(self,):
		try:
			with open(self.file_name) as infile:
				j = json.load(infile, object_hook=self.ascii_encode_dict)
		except:
			j={}
		return j

	def write_json(self ,dic):
		with open(self.file_name, 'w') as f:
		    json.dump(dic, f)

	def reflesh(self, ):
		j = self.load_json()
		for i in j:
			self.remove_pid(i)

	def killall(self, ):
		j = self.load_json()
		for i in j:
			kill_process(i)

	def add_pid(self,pid,state):	
		j=self.load_json()

		try:
			j["%s"%pid].update(state)			
		except:
			pid_dic={"%s"%pid:state}
			j.update(pid_dic)

		self.write_json(j)
		return j


	def remove_pid(self,pid,force=False):
		pid=int(pid)
		if (os.path.exists(self.file_name) and not self.pid_exists(pid)) or force:
			j=self.load_json()
			del j["%s"%pid]
			self.write_json(j)



if __name__ == '__main__':
	pid =os.getpid()

	state = {"flag" : True,"time":datetime.now().strftime("%Y/%m/%d %H:%M:%S")}
	jm=jobmng("jobpid")
	
	jm.add_pid(pid,state)
	# time.sleep(10)
	for i in range(6):
		print i
		jm.add_pid(pid,{"status":i})
		time.sleep(1)

	jm.remove_pid(pid,force=True)
