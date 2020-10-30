print('A good day to learn python')
print("Python is fun")
print('Hello '+ 'Mncedi!!')

greeting = "Hello "
name = input('Please enter your name: ')
print(greeting + name)
age = 24
# print(greeting+' '+name+' you are '+age)    //error for it's a strongly typed language
print(type(age))
print(type(greeting))


print()
print("""
###################################################################################
########### f-strings #############################################################
######## not available for use from version 3.4 and lower ###############################
""")
print(name + f" is {age} years old")
print(f"Pi is approximately {22 / 7:12.50f}")
print()
pi = 22 / 7
print(f"Pi is approximately: {pi:12.50f}")