from tkinter import *
from tkinter import messagebox
import hashlib
import cv2
import dlib
import face_recognition
import numpy as np
import json
import mysql.connector
from mysql.connector import Error


def login():
    global login_screen
    global username_entered
    global password_entered
    global username_login_entry
    global password_login_entry

    login_screen = Tk()
    login_screen.title("Обробка обличчя")
    login_screen.geometry("280x180")

    Label(login_screen, text="").pack()

    username_entered = StringVar()
    password_entered = StringVar()

    Label(login_screen, text="Електронна пошта: ").pack()
    username_login_entry = Entry(login_screen, textvariable=username_entered)
    username_login_entry.pack()
    Label(login_screen, text="Пароль:  ").pack()
    password_login_entry = Entry(login_screen, textvariable=password_entered, show='*')
    password_login_entry.pack()
    Label(login_screen, text="").pack()
    Button(login_screen, text="Підтвердити", width=10, height=1, command=login_verify).pack()
    login_screen.mainloop()

def login_verify():
    global workerId

    username = username_entered.get()
    password = password_entered.get()
    username_login_entry.delete(0, END)
    password_login_entry.delete(0, END)

    data = getLoginPasswordList()
    workerId = -1
    res = hashlib.md5(password.encode())
    encryptedPassword = res.hexdigest()
    for d in data:
        if d[1] == username:
            if d[2] == encryptedPassword:
                workerId = d[0]
                break
    if workerId == -1:
        user_data_incorrect()
    else:
        checkUnencodedWorkerFace(workerId)

def user_data_incorrect():
    global user_data_incorrect_screen
    user_data_incorrect_screen = Toplevel(login_screen)
    user_data_incorrect_screen.title("Обробка обличчя")
    user_data_incorrect_screen.geometry("250x70")
    Label(user_data_incorrect_screen, text="Користувача не знайдено. \nПеревірте правильність введення даних").pack()
    Button(user_data_incorrect_screen, text="OK", command=delete_user_data_incorrect_screen).pack()

def delete_user_data_incorrect_screen():
    user_data_incorrect_screen.destroy()

def delete_encodings_not_empty_screen():
    encodings_not_empty_screen.destroy()

def encodings_are_empty():
    global encodings_empty_screen
    encodings_empty_screen = Toplevel(login_screen)
    encodings_empty_screen.title("Обробка обличчя")
    encodings_empty_screen.geometry("250x100")
    Label(encodings_empty_screen, text="Даних із фото немає. \nВам необхідно їх обчислити").pack()
    Button(encodings_empty_screen, text="Обчислити", command=setEncodings).pack()

def encodings_not_empty():
    global encodings_not_empty_screen
    encodings_not_empty_screen = Toplevel(login_screen)
    encodings_not_empty_screen.title("Обробка обличчя")
    encodings_not_empty_screen.geometry("180x100")
    Label(encodings_not_empty_screen, text="Дані з фото уже вилучені. \n").grid(row=0,column=0,columnspan=2)

    Button(encodings_not_empty_screen, text="Закрити", command=delete_encodings_not_empty_screen).grid(row=1,column=0,pady=10, padx=10)
    Button(encodings_not_empty_screen, text="Оновити дані", command=updateEncodings).grid(row=1,column=1,pady=10, padx=10)

def success(message):
    messagebox.showinfo("info", message)

def error(message):
    messagebox.showerror("error", message)


def detectFace(detector, image, inHeight=300, inWidth=0):
    imageDlibHog = image.copy()
    imageHeight = imageDlibHog.shape[0]
    imageWidth = imageDlibHog.shape[1]

    if not inWidth:
        inWidth = int((imageWidth / imageHeight) * inHeight)

    scaleHeight = imageHeight / inHeight
    scaleWidth = imageWidth / inWidth

    imageDlibHogSmall = cv2.resize(imageDlibHog, (inWidth, inHeight))
    imageDlibHogSmall = cv2.cvtColor(imageDlibHogSmall, cv2.COLOR_BGR2RGB)

    faceRects = detector(imageDlibHogSmall, 0)
    borders = []
    for faceRect in faceRects:
        cvRect = [
            int(faceRect.left() * scaleWidth),
            int(faceRect.top() * scaleHeight),
            int(faceRect.right() * scaleWidth),
            int(faceRect.bottom() * scaleHeight),
        ]
        borders.append(cvRect)
        cv2.rectangle(
            imageDlibHog,
            (cvRect[0], cvRect[1]),
            (cvRect[2], cvRect[3]),
            (0, 248, 252),
            1
        )
    return imageDlibHog, borders

def getEncodingsLocations(image):
    hogFaceDetector = dlib.get_frontal_face_detector()

    outDlibHog, faceBorders = detectFace(hogFaceDetector, image)

    imageSmall = cv2.resize(image, (0, 0), None, 0.25, 0.25)
    imageSmall = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

    encFaces = []

    for eachFace in faceBorders:
        currFaceLocation = [(eachFace[1], eachFace[2], eachFace[3], eachFace[0])] # facesCurFrame
        encCurrFace = face_recognition.face_encodings(imageSmall, known_face_locations=currFaceLocation) # encodesCurFrame
        encFaces.append(encCurrFace[0])
    return encFaces, faceBorders

class NumpyArrayEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, np.ndarray):
            return obj.tolist()
        return json.JSONEncoder.default(self, obj)

def getImage(worker_id, photo_name):
    try:
        SQLStatement = "SELECT photo FROM workers WHERE worker_id = %s"
        mycursor.execute(SQLStatement, (worker_id,))
        record = mycursor.fetchall()
        for row in record:
            imageData = row[0]
            with open(photo_name, 'wb') as file:
                file.write(imageData)
            image = cv2.imread(photo_name)
            return image

    except mysql.connector.Error as error:
        error("Помилка зчитування {}".format(error))

def checkUnencodedWorkerFace(workerId):
    try:
        mycursor.execute(f"SELECT count(worker_id) from recognition_data WHERE worker_id={workerId}")
        result = mycursor.fetchone()

        if result[0] == 0:
            encodings_are_empty()
        else:
            encodings_not_empty()
    except:
        error("Виникла помилка під час пошуку даних")

def setEncodings():
    encodings_empty_screen.destroy()
    imageFromDb = getImage(workerId, "image.jpg")
    encodings, locations = getEncodingsLocations(imageFromDb)
    try:
        locationList = f"{locations[0][0]},{locations[0][1]},{locations[0][2]},{locations[0][3]}"  # split(" ")
        mycursor = conn.cursor(buffered=True)
        numpyData = {"array": encodings[0]}
        encodedNumpyData = json.dumps(numpyData, cls=NumpyArrayEncoder)
        # mycursor.execute("DESCRIBE recognition_data")
        SQLStatement = "INSERT INTO recognition_data (worker_id, face_encodings, face_location) VALUES (%s,%s,%s)"
        mycursor.execute(SQLStatement, (workerId, encodedNumpyData, locationList))
        conn.commit()
        success("Збережено")
    except:
        error("Не вдалось зберегти. Зображення некоректне")

def updateEncodings():
    encodings_not_empty_screen.destroy()
    imageFromDb = getImage(workerId, "image.jpg")
    encodings, locations = getEncodingsLocations(imageFromDb)
    try:
        locationList = f"{locations[0][0]},{locations[0][1]},{locations[0][2]},{locations[0][3]}"  # split(" ")
        mycursor = conn.cursor(buffered=True)
        numpyData = {"array": encodings[0]}
        encodedNumpyData = json.dumps(numpyData, cls=NumpyArrayEncoder)
        SQLStatement = "UPDATE recognition_data SET face_encodings=%s, face_location=%s WHERE worker_id=%s"
        mycursor.execute(SQLStatement, (encodedNumpyData, locationList, workerId))
        conn.commit()
        success("Оновлено")
    except:
        error("Не вдалось оновити. Зображення некоректне")

def getLoginPasswordList():
    try:
        mycursor = conn.cursor()
        mycursor.execute("SELECT worker_id, email, password FROM workers")
        rows = mycursor.fetchall()
        return rows
    except Error as e:
        error(e)


try:
    global conn
    global mycursor

    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        passwd="",
        database="attendance"
    )
    mycursor = conn.cursor()
    login()

except Error as e:
    error(e)

finally:
    if conn.is_connected():
        mycursor.close()
        conn.close()

