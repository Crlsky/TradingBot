import sqlalchemy as db
import pandas as pd

from datetime import datetime, timedelta 
from sklearn.linear_model import LinearRegression

def dataCollector(dateFrom, dateTo, operationType):
    if operationType == "Both" : operationType = "%%"
    con = db.create_engine('mysql+mysqldb://username:password@localhost:3306/trading')
    dataQuerry = "SELECT date, rate, type FROM transactionHistory WHERE DATE BETWEEN '"+dateFrom+"' AND '"+dateTo+"' AND type LIKE '"+operationType+"' ORDER BY id ASC"
    dataData = pd.read_sql_query(dataQuerry, con)
    dataDf = pd.DataFrame(dataData, columns=['date', 'rate', 'type'])
    return dataDf


def regresion(dateFrom, dateTo, operationType):
    dataFrame = dataCollector(dateFrom, dateTo, operationType)
    X = dataFrame[['date']]
    Y = dataFrame[['rate']]

    model = LinearRegression()
    model.fit(X,Y,sample_weight=None)

    coefficient = model.coef_[0][0]
    return coefficient*pow(10,10)

def timeRange(ammountOfTime):
    if ammountOfTime:
        time = datetime.now()-timedelta(minutes=60*ammountOfTime)
        return time.strftime("%Y-%m-%d %H:%M:%S")
    else:
        time = datetime.now()+timedelta(hours=2)
        return time.strftime("%Y-%m-%d %H:%M:%S")

def multiRegresion(dateFrom, dateTo):
    print("For bought: ")
    print(regresion(dateFrom, dateTo, "Buy"))
    print("For sold: ")
    print(+regresion(dateFrom, dateTo, "Sell"))
    print("For both: ")
    print(+regresion(dateFrom, dateTo, "Both"))

multiRegresion(timeRange(0.5),timeRange(0))
