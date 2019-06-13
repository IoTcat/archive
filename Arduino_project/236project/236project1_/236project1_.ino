/* *****************************************************************
 * this is the code for control unit 236
 * 
 * *****************************************************************/

//declear the moudle is WIFI


#include <IRremote.h>
 
int PIN_RECV = 11;
IRrecv irrecv(PIN_RECV);
decode_results results;

//include function set


//define overal var




//declear var
int swi,swi1 ,i,ii,a,b,c,light= 0;
long timer,timer1,timer2,time3=0;
float distance;



//button1-function
void button1_callback() 
{

    digitalWrite(13,!digitalRead(13));
    delay(50);
    Serial.print("lllfan");


}


//button2-function
void button2_callback() 
{

    digitalWrite(7,!digitalRead(7));
    if(digitalRead(7)){ }else{    
    digitalWrite(8,HIGH);
    swi=1;
    timer=millis()+20000;
    }

}


void button2_end()
{
      digitalWrite(8,LOW);
      swi=0;
}


//button1-function
void button3_callback() 
{

    digitalWrite(8,HIGH);
    delay(100);
    swi=1;
    timer=millis()+20000;

}


//button1-function
void button4_callback() 
{

    digitalWrite(5,!digitalRead(5));
    delay(50);


}


void alert()
{
    for(int i=20;i<=800;i++)
    {    
    
     for(int k=0;k<12;k++)
     {
     digitalWrite(6,HIGH);
      delayMicroseconds(i);  
       digitalWrite(6,LOW);
            delayMicroseconds(i);  
     }
    }  
    
    delay(500);         
    for(int i=800;i>=20;i--)  
    {    
     for(int k=0;k<12;k++)
     {
     digitalWrite(6,HIGH);
      delayMicroseconds(i);  
       digitalWrite(6,LOW);
            delayMicroseconds(i);  
     }
    }
}

//main setup function
void setup() 
{
    Serial.begin(115200);
    
    pinMode(13, OUTPUT);  //control the fan D5
    pinMode(7, OUTPUT);  //control the light D4
    pinMode(8, OUTPUT);  //control the ledR D6
    pinMode(5, OUTPUT);  //control the ledB D2

    pinMode(12, INPUT);  //control the ultr D10
    pinMode(4, OUTPUT);  //control the ultr D9
    pinMode(10, INPUT);  //get the fog D8
    pinMode(9, INPUT);  //get the light value D7
    pinMode(3, OUTPUT);  //controlBed output
    pinMode(2, INPUT);  //get Bed input
    pinMode(6, OUTPUT);  //control the Alert D3
    
    //set the light paraperation
    digitalWrite(13, HIGH);

light=1;

    //connect to WIFI
  

    //set interrupt


  irrecv.enableIRIn();
    

}


int aaa=15;

int aaaa=33;

//main loop function
void loop() {
Serial.println(analogRead(A0));
if(analogRead(A0)>500) light=1;
else light=0;

  if (irrecv.decode(&results)) {
    Serial.print("rrrrrrrrrrrrrrrrrrrrr");
    Serial.println(results.value);
    irrecv.resume();
  if (results.value == 16750695||results.value == 2538093563


) {
      button1_callback();
      delay(600);
    }

     if (results.value== 16712445||results.value==3622325019

) {
      button2_callback();
      delay(600);
    }
         if (results.value == 1675069
) {
      button4_callback();
      delay(600);
    }
  }

  
  while (Serial.available() > 0) {
    c=Serial.read();
    if (c == 'f') {
      button1_callback();
      delay(600);
    }

     if (c== 'l') {
      button2_callback();
      delay(600);
    }
     if (c == 'r') {
      button3_callback();
      delay(600);
    }
     if (c == 'b') {
      button4_callback();
      delay(600);
    }
     if (c == 't') {
          digitalWrite(8,HIGH);
    swi=1;
    timer=millis()+20000;

      delay(600);
    }

         if (c == 'k') {
      light=1;
      delay(600);
    }
             if (c == 'g') {
      light=0;
      delay(600);
    }
  }




c=0;


a=0;


for(i=0;i<50;i++)
{

    // 产生一个10us的高脉冲去触发TrigPin
        digitalWrite(3, LOW);
        delayMicroseconds(2);
        digitalWrite(3, HIGH);
        delayMicroseconds(10);
        digitalWrite(3, LOW);


        distance = pulseIn(2, HIGH) / 58.00;
 
        timer1=distance;
      // Serial.print(timer1);
     // Serial.println();

     if(timer1<60){a++;}
}

Serial.println(a);

if(a>aaa+7&&swi!=1){  button3_callback();}

if(a>1&&aaa>7)
  aaa = aaa*0.999+a*0.001;

 Serial.println("uuuuu");
 Serial.println(a);
 Serial.println(aaa);
if(swi==1)
{

  if(timer<millis())
  {
    digitalWrite(8,LOW);
          swi=0;
  }
 
}
  Serial.print("ccccccccccccccc");
Serial.println(light);

if(swi1==1)
{
  Serial.print("llllllllllightsensor");Serial.println(light);
  if(light){
  if(timer2<millis()||swi==1)
  {
        digitalWrite(5,LOW);Serial.print("gggggggggggggggggggggg");Serial.println(millis());Serial.println(timer2);
        swi=0;
      swi1=0;  
  }
   }
  else
  {
     digitalWrite(5,LOW);Serial.print("kkkkkkkkkkkkkkkkkkkkkkkkk");
      swi1=0;  
  }
}

a=0;


for(i=0;i<50;i++)
{

    // 产生一个10us的高脉冲去触发TrigPin
        digitalWrite(4, LOW);
        delayMicroseconds(2);
        digitalWrite(4, HIGH);
        delayMicroseconds(10);
        digitalWrite(4, LOW);


        distance = pulseIn(12, HIGH) / 58.00;
 
        timer1=distance;
   //     Serial.print(timer1);
   //   Serial.println();

     if(timer1<70){a++;}
}

Serial.println(a);


i=digitalRead(5);
if(a>aaaa+7)
{
  Serial.print("iiiiiiiiiiiiiiiiiiii");
Serial.println(i);

  if(i==0)
  { Serial.print("iiiiii9999999iiii");

  if(light){
    digitalWrite(5,HIGH);
    delay(50);
    swi1=1;
    timer2=millis()+80000;Serial.println("77777777777777777777777");Serial.println(millis());Serial.println(timer2);
    

  }
  }
  else
  {
        timer2=millis()+80000;Serial.println("8888888888888888888");
  }
}

if(a>1&&aaaa>19)
aaaa=a*0.001+aaaa*0.999;
 Serial.println("aaaa");
 Serial.println(a);
 Serial.println(aaaa);


if(digitalRead(10)==0)
{
  alert();

}

}
