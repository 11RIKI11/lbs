
Dataset <- readXL("C:/Users/maxbo/Documents/Study/3 курс/ИАД/Данные_2.xlsx", rownames=TRUE, header=TRUE, na="", 
  sheet="Лист1", stringsAsFactors=TRUE)
cor(Dataset[,c("Ёмкость..А.ч.","КПД....","Напряжение..В.","Срок_службы..циклы.","Стоимость....кВт.ч.",
  "Удельная_энергия..Вт.ч.кг.")], use="complete")
scatterplotMatrix(~Ёмкость..А.ч.+КПД....+Напряжение..В.+Срок_службы..циклы.+Стоимость....кВт.ч.+Удельная_энергия..Вт.ч.кг.,
   regLine=FALSE, smooth=FALSE, diagonal=list(method="density"), data=Dataset)
RegModel.1 <- lm(Срок_службы..циклы.~Ёмкость..А.ч., data=Dataset)
summary(RegModel.1)
crPlots(RegModel.1, smooth=list(span=0.5))
RegModel.2 <- lm(Удельная_энергия..Вт.ч.кг.~Напряжение..В., data=Dataset)
summary(RegModel.2)
crPlots(RegModel.2, smooth=list(span=0.5))
RegModel.4 <- lm(КПД....~Удельная_энергия..Вт.ч.кг., data=Dataset)
summary(RegModel.4)
RegModel.5 <- lm(Удельная_энергия..Вт.ч.кг.~Напряжение..В., data=Dataset)
summary(RegModel.5)
RegModel.6 <- lm(Напряжение..В.~Удельная_энергия..Вт.ч.кг., data=Dataset)
summary(RegModel.6)
crPlots(RegModel.6, smooth=list(span=0.5))
RegModel.7 <- lm(КПД....~Удельная_энергия..Вт.ч.кг., data=Dataset)
summary(RegModel.7)
crPlots(RegModel.7, smooth=list(span=0.5))
Dataset_test <- readXL("C:/Users/maxbo/Documents/Study/3 курс/ИАД/Пример_Данные для 2.3.xlsx", rownames=TRUE, 
  header=TRUE, na="", sheet="Лист1", stringsAsFactors=TRUE)
RegModel.8 <- lm(реальное.ВВП~бедность+безработица+образовании+общест...ое.развитие+ожидаемая.продол.ть, 
  data=Dataset_test)
summary(RegModel.8)
crPlots(RegModel.8, smooth=list(span=0.5))
library(MASS, pos=16)
stepwise(RegModel.8, direction='forward', criterion='AIC')
stepwise(RegModel.8, direction='forward/backward', criterion='AIC')
vif(RegModel.8)
round(cov2cor(vcov(RegModel.8)), 3) # Correlations of parameter estimates
RegModel.9 <- lm(Срок_службы..циклы.~Ёмкость..А.ч.+КПД....+Напряжение..В.+Стоимость....кВт.ч.+Удельная_энергия..Вт.ч.кг., data=Dataset)
summary(RegModel.9)
crPlots(RegModel.9, smooth=list(span=0.5))
stepwise(RegModel.9, direction='forward', criterion='AIC')
stepwise(RegModel.9, direction='forward/backward', criterion='AIC')
vif(RegModel.9)
round(cov2cor(vcov(RegModel.9)), 3) # Correlations of parameter estimates

