for i in range(1, 13):
    print("No. {0:2} square is {1:3} and cubed is {2:4}".format(i, i**2, i**3))

# formatted and left < aligned (right aligned is >) center align ^
for i in range(1, 13):
    print("No. {0:2} square is {1:<3} and cubed is {2:^4}".format(i, i**2, i**3))

print()

print("Pi is approximately {0:12}".format(22 / 7))
print("Pi is approximately {0:12f}".format(22 / 7))
print("Pi is approximately {0:12.50f}".format(22 / 7))
print("Pi is approximately {0:52.50f}".format(22 / 7))
print("Pi is approximately {0:62.50f}".format(22 / 7))
print("Pi is approximately {0:72.50f}".format(22 / 7))

########################################################################################################
######################################## f-strings #####################################################
####################### won't run on Python versions lower than 3.6 ###############################################

# got to strings file

# f-strings won't work with python 3.4 or earlier versions of python


