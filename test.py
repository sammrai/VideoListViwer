import time,os
import json
import signal

class jobmng(object):
	"""docstring for ClassName"""
	def __init__(self):
		pass
		
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

	def check_pid(self, pid):        
	    if os.name=="nt":return self.pid_running_win(pid)
	    if os.name=="posix":return self.pid_running_unix(pid)

	def ascii_encode_dict(self, data):
	    ascii_encode = lambda x: x.encode('ascii') if isinstance(x, unicode) else x
	    return dict(map(ascii_encode, pair) for pair in data.items())


	def kill_process_win(self, pid):
		if check_pid(pid):
			os.kill(pid, signal.SIGTERM)
			return 0
		else:
			return 1

	def kill_process_unix(self, pid):
		if check_pid(pid):
			os.kill(pid, signal.SIGKILL)
			return 0
		else:
			return 1

	def kill_process(self, file_name,pid):
		pid=int(pid)
		self.remove_pid(file_name,pid,force=True)
		if os.name=="nt":return kill_process_win(pid)
		if os.name=="posix":return kill_process_unix(pid)


	def load_json(self, file_name):
		try:
			with open(file_name) as infile:
				j = json.load(infile, object_hook=self.ascii_encode_dict)
		except:
			j={}
		return j

	def write_json(self, file_name,dic):
		with open(file_name, 'w') as f:
		    json.dump(dic, f)

	def reflesh(self, file_name):
		j = self.load_json(file_name)
		for i in j:
			self.remove_pid(file_name,i)

	def killall(self, file_name):
		j = self.load_json(file_name)
		for i in j:
			kill_process(file_name,i)

	def add_pid(self, file_name,pid_dic):
		self.reflesh(file_name)
		
		j=self.load_json(file_name)
		j.update(pid_dic)
		self.write_json(file_name,j)
		return j


	def remove_pid(self, file_name,pid,force=False):
		pid=int(pid)
		if (os.path.exists(file_name) and not self.check_pid(pid)) or force:
			j=self.load_json(file_name)
			del j["%s"%pid]
			self.write_json(file_name,j)
			# print "#"

if __name__ == '__main__':
	pid =os.getpid()
	from datetime import datetime
	date = datetime.now().strftime("%Y/%m/%d %H:%M:%S")

	pid_dic = {pid:{"flag" : True,"time":date}}
	file_name="jobpid"
	jm=jobmng()

	jm.reflesh(file_name)
	jm.add_pid(file_name,pid_dic)
	for i in range(6):
		print i
		# f=open("test_%03d"%i,"w")
		# f.close()
		# print os.system("touch %03d"%i)
		time.sleep(1)

	jm.remove_pid(file_name,pid,force=True)
