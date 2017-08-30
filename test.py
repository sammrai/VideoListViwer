import time,os
pid = os.getpid()

if os.path.exists("jobpid"):os.remove("jobpid")

f=open("jobpid","w")
f.write("%d"%pid)
f.close()


for i in range(6):
	print i
	f=open("test_%03d"%i,"w")
	f.close()
	# print os.system("touch %03d"%i)
	time.sleep(10)


os.remove("jobpid")