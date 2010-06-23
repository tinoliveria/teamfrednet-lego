package org.frednet.nxt.server.main;

import gnu.io.CommPort;
import gnu.io.CommPortIdentifier;
import gnu.io.NoSuchPortException;
import gnu.io.PortInUseException;
import gnu.io.SerialPort;
import gnu.io.UnsupportedCommOperationException;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
/**
 * interface class for comport.
 * 
 * Using rxtx
 * @author Marc Brakels
 *
 */
public class Port {
	public Port() {
		super();
	}

	SerialPort serialPort;
	
/**
 * This will setup connection with comport
 * @param portName Comport name like COM1
 * @return Retrun result true or false
 * @throws Exception
 */
	boolean connect(String portName) {
		CommPortIdentifier portIdentifier;
		try {
			//TODO: find out which this is so slow
			portIdentifier = CommPortIdentifier.getPortIdentifier(portName);
		
		if (portIdentifier.isCurrentlyOwned()) {
			System.out.println("Error: Port is currently in use");
			return false;
		} else {
			CommPort commPort;
			
				commPort = portIdentifier.open(this.getClass().getName(),
						2000);
			
		
			if (commPort instanceof SerialPort) {
				serialPort = (SerialPort) commPort;
				serialPort.setSerialPortParams(9600, SerialPort.DATABITS_8,
						SerialPort.STOPBITS_1, SerialPort.PARITY_NONE);
				
				return true;

			} else {
				System.out
						.println("Error: Only serial ports are handled by this example.");
				return true;
			}
		}
		} catch (NoSuchPortException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
			return false;
		} catch (PortInUseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return false;
		} catch (UnsupportedCommOperationException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return false;
		}
		
	}

	public Boolean IsAlive() {
		return true;
	}

	public Boolean Disconnect() {
		return true;
	}
/** 
 * This function will send a message to Nxt/comport
 * @param 	msg		Message that need to be send
 * @param 	length	Length of message
 * @return			result of action: True or False
 */
	public boolean SendMessage(byte[] msg,int length) {
		
		OutputStream out; 
		try {
			out = serialPort.getOutputStream();
			//For is need because null byte
			for(int i=0; i < length;i++){
                    out.write(msg[i]);
			}
			
				//out.flush();
			
						return true;
		} catch (IOException e) {
			
			e.printStackTrace();
			return false;
		}
	}
/**
 * This function will read first message recieved.
 * @return return response in a byte array
 */
	public byte[] ReadMessage() {
		InputStream in;
		try {
			in = serialPort.getInputStream();
		} catch (IOException e1) {
			
			e1.printStackTrace();
			return null;
		}
		byte[] buffer = new byte[1024];
		int len = -1;
		int count = 0;
		try {
while((len == -1) && count < 100){
	len = in.read();
	try {
		Thread.sleep(20);
	} catch (InterruptedException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	}
	count++;
}
if(len != -1){
in.read();
			len = in.read(buffer,0,len);
			
				return buffer;
}else{
	return null;
}

		} catch (IOException e) {
			e.printStackTrace();

			return null;
		}
		
	}

	
	

}
