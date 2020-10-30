string1 = "he's "
string2 = "probably "
string3 = "pining "
string4 = "for the "
string5 = "fjords "

print(string1 + string2 + string3 + string4 + string5)
print("he's " "probably " "pining " "for the " "fjords")

print("hello "* 5)
print("Ola Mfethu " * (5 + 2))
print("Ola Mfethu " * 5 + "4")

today = "Friday"
print("day" in today)       #true
print("Fri" in today)       #true
print("thu" in today)       #false
print("parrot" in "jford")  #false