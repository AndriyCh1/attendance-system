import cv2
import dlib
import face_recognition
import numpy as np
import json
from datetime import datetime, date
import mysql.connector
from mysql.connector import Error
from tkinter import *
from tkinter import messagebox


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
            (0, 255, 0),
            1
        )
    return imageDlibHog, borders

def compareEncodings(encodingsList1, encodingsList2):
    tolerance = 0.6
    matchesList = []
    for encodings in encodingsList1:
        faceDistance = face_recognition.api.face_distance(encodingsList2, encodings)
        faceMatches = list(faceDistance <= tolerance)
        matchesList.append(faceMatches)
    return matchesList

def getEncodingsFromDb(workerId):
    try:
        mycursor = conn.cursor()

        SQLStatement = f"SELECT face_encodings FROM recognition_data WHERE worker_id = {workerId}"
        mycursor.execute(SQLStatement, workerId)

        for row in mycursor:
            strList = json.loads(row[0])
            print(strList)
            strListElement = strList["array"]
            numpyEncodings = [np.array(strListElement)]
            return numpyEncodings

    except Error as e:
        print(e)

def realTimeRecognition(workerIds, encodeListKnown):
    cap = cv2.VideoCapture(0,cv2.CAP_DSHOW)

    hogFaceDetector = dlib.get_frontal_face_detector()

    while True:
        hasFrame, frame = cap.read()

        if not hasFrame: break

        outDlibHog, faceBorders = detectFace(hogFaceDetector, frame)

        imageSmall = cv2.resize(frame, (0, 0), None, 0.25, 0.25)
        imageSmall = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

        foundIds = []

        for eachFace in faceBorders:
            facesCurFrame = [(eachFace[1], eachFace[2], eachFace[3], eachFace[0])]

            encodesCurFrame = face_recognition.face_encodings(imageSmall, known_face_locations=facesCurFrame)
            compared = compareEncodings(encodesCurFrame, encodeListKnown)

            for r in range(len(compared)):
                for c in range(len(compared[r])):
                    if compared[r][c]:
                        foundIds.append(workerIds[c])

            if len(foundIds):
                cv2.imshow("Finding face...", outDlibHog)
                cv2.waitKey(500)
                cap.release()
                cv2.destroyAllWindows()
                return foundIds

        cv2.imshow("Finding face...", outDlibHog)
        k = cv2.waitKey(5)
        if k == 27:
            break

    cap.release()
    cv2.destroyAllWindows()
    return []


def success(message):
    messagebox.showinfo("info", message)

def error(message):
    messagebox.showerror("error", message)

def arrive():
    realTimeMark("arrive")

def leave():
    realTimeMark("leave")

def markAction():
    global mark_screen

    mark_screen = Tk()
    mark_screen['bg'] = '#87b3fa'
    mark_screen.title("")

    label = Label(mark_screen, text="ВІДМІТИТИСЬ?")
    label.grid(row=0,column=0,columnspan=2,pady=10, padx=10)
    b1 = Button(mark_screen, text="ПРИБУВ", width=10, height=1, command=arrive)
    b2 = Button(mark_screen, text="ПОКИДАЮ", width=10, height=1, command=leave)
    b1.grid(row=1,column=0,pady=10, padx=10)
    b2.grid(row=1,column=1,pady=10, padx=10)

    mark_screen.mainloop()

def realTimeMark(action):
    encodings = []
    workerIds = []
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
        mycursor.execute("SELECT worker_id FROM recognition_data")
        rows = mycursor.fetchall()

        for row in rows:
            encoding = getEncodingsFromDb(row[0])[0]
            encodings.append(encoding)
            workerIds.append(row[0])

        foundWorkers = realTimeRecognition(workerIds, encodings)

        now = datetime.now()
        currentDay = now.strftime("%Y-%m-%d")
        currentTime = now.strftime("%H:%M:%S")

        mycursor.execute("SELECT status_arrival, status_leaving, record_id FROM records WHERE date='"+currentDay+"' AND worker_id='"+str(foundWorkers[0])+"'")

        fetchedStatus = mycursor.fetchone()

        if fetchedStatus is not None:
            isArrived = True if fetchedStatus[0] == 1 else False
            isLeaved = True if fetchedStatus[1] == 1 else False
            if action == "arrive":
                if not isArrived:
                    SQLStatement = "UPDATE `records` SET `status_arrival`= '1', `time_arrival`='%s WHERE `record_id`=%s"
                    mycursor.execute(SQLStatement, (currentTime,str(fetchedStatus[2])))
                    conn.commit()
            elif action == "leave":
                if not isLeaved:
                    SQLStatement = "UPDATE `records` SET `status_leaving`= '1', `time_leaving`=%s WHERE `record_id`=%s"
                    mycursor.execute(SQLStatement, (currentTime, str(fetchedStatus[2])))
                    conn.commit()
        else:
            SQLStatement = "INSERT INTO `records` (`worker_id`, `status_arrival`, `date`, `time_arrival`, `status_leaving`, `time_leaving`) VALUES (%s, %s, %s, %s, %s, %s)"
            if action == "arrive":
                mycursor.execute(SQLStatement, (foundWorkers[0], '1', currentDay, currentTime, '0', '00:00:00'))
                conn.commit()
            elif action == "leave":
                mycursor.execute(SQLStatement, (foundWorkers[0], '0', currentDay, '00:00:00', '1', currentTime))
                conn.commit()

    except Error as e:
        error(e)

    finally:
         mycursor.close()
         conn.close()

markAction()
