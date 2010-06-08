package org.frednet.nxt.server.main;

import java.lang.ref.Reference;

public class NxtControl {
	/// <summary>
    /// Enumeration of NXT brick sensor ports.
    /// </summary>
	public enum Sensor
    {
		/// <summary>
        /// First sensor.
        /// </summary>
        First((byte)0x00),
        /// <summary>
        /// Second sensor.
        /// </summary>
        Second((byte)0x01),
        /// <summary>
        /// Third sensor.
        /// </summary>
        Third((byte)0x02),
        /// <summary>
        /// Fourth sensor.
        /// </summary>
        Fourth((byte)0x03);
        byte Sensor;
        Sensor(byte Sensor){
        	this.Sensor = Sensor;
        }
    
    }
	/// <summary>
    /// Enumeration of NXT brick sensor types.
    /// </summary>
    /// 
    public enum SensorType
    {
        /// <summary>
        /// No sensor.
        /// </summary>
        NoSensor((byte)0x00),
        /// <summary>
        /// Switch (touch) sensor.
        /// </summary>
        Switch((byte)0x01),
        /// <summary>
        /// Temperature sensor.
        /// </summary>
        Temperature((byte)0x02),
        /// <summary>
        /// Reflection sensor.
        /// </summary>
        Reflection((byte)0x03),
        /// <summary>
        /// Angle sensor.
        /// </summary>
        Angle((byte)0x04),
        /// <summary>
        /// Light activity sensor.
        /// </summary>
        LightActive((byte)0x05),
        /// <summary>
        /// Light inactivity sensor.
        /// </summary>
        LightInactive((byte)0x06),
        /// <summary>
        /// Sound sensor (in dB).
        /// </summary>
        SoundDB((byte)0x07),
        /// <summary>
        /// Sound sensor (in dBA).
        /// </summary>
        SoundDBA((byte)0x08),
        /// <summary>
        /// Custom sensor.
        /// </summary>
        Custom((byte)0x09),
        /// <summary>
        /// Low speed sensor.
        /// </summary>
        Lowspeed((byte)0x0A),
        /// <summary>
        /// Low speed sensor (9V).
        /// </summary>
        Lowspeed9V((byte)0x0B);
        byte SensorType;
        SensorType(byte SensorType){this.SensorType = SensorType;}
    }
    
    /// <summary>
    /// Enumeration of NXT brick sensor modes.
    /// </summary>
    /// 
    public enum SensorMode
    {
        Raw((byte)0),
        /// <summary>
        /// Boolean mode.
        /// </summary>
        Boolean((byte)32),//0x20
        /// <summary>
        /// Number of boolean transitions.
        /// </summary>
        TransitionCounter((byte)64),
        /// <summary>
        /// Periodic counter (number of boolean transitions divided by two).
        /// </summary>
        PeriodicCounter((byte)96),
        /// <summary>
        /// ??? (lack of documentation from Lego).
        /// </summary>
        PCTFullScale((byte)128),
        /// <summary>
        /// Celsius mode.
        /// </summary>
        Celsius((byte)160),
        /// <summary>
        /// Fahrenheit mode.
        /// </summary>
        Fahrenheit((byte)192),
        /// <summary>
        /// Angle steps mode.
        /// </summary>
        AngleSteps((byte)224);
        byte SensorMode;
        SensorMode(byte SensorMode){
        	this.SensorMode = SensorMode;
        }
    }

    /// <summary>
    /// Structure, which describes sensor's values received from NXT brick's sensor port.
    /// </summary>
    /// 
    public class Holder<SensorValues>
    {
        /// <summary>
        /// Specifies if data value should be treated as valid data.
        /// </summary>
        public Boolean IsValid;
        /// <summary>
        /// Specifies if calibration file was found and used for <see cref="Calibrated"/>
        /// field calculation.
        /// </summary>
        public Boolean IsCalibrated;
        /// <summary>
        /// Sensor type.
        /// </summary>
        public SensorType SensorType;
        /// <summary>
        /// Sensor mode.
        /// </summary>
        public SensorMode SensorMode;
        /// <summary>
        /// Raw A/D value (device dependent).
        /// </summary>
        //TODO: unsigned
        public short Raw;
        /// <summary>
        /// Normalized A/D value (sensor type dependent), [0, 1023].
        /// </summary>
        public short Normalized;
        /// <summary>
        /// Scaled value (sensor mode dependent).
        /// </summary>
        public short Scaled;
        /// <summary>
        /// Value scaled according to calibration.
        /// </summary>
        /// 
        /// <remarks><note>According to Lego notes the value is currently unused.</note></remarks>
        /// 
        public short Calibrated;
    }

    /// <summary>
    /// Enumeration of NXT brick motor ports.
    /// </summary>
    /// 
    public enum Motor
    {
        A((byte)0),B((byte)1),C((byte)2),All((byte)0xFF);
        byte Motor;
        Motor(byte Motor){
        	this.Motor = Motor;
        }
    }

    
    public enum MotorMode
    {
        /// <summary>
        /// Mode is not set.
        /// </summary>
        None((byte)0x00),
        /// <summary>
        /// Turn on the motor.
        /// </summary>
        On((byte)0x01),
        /// <summary>
        /// Brake.
        /// </summary>
        Brake((byte)0x02),
        /// <summary>
        /// Turn on regulated mode.
        /// </summary>
        Regulated((byte)0x04);
        byte MotorMode;
        MotorMode(byte MotorMode){this.MotorMode = MotorMode;}
    }

    /// <summary>
    /// Enumeration of motor regulation modes.
    /// </summary>
    /// 
    public enum MotorRegulationMode
    {
        Idle((byte)0),Speed((byte)1),Sync((byte)2);
        byte MotorRegulationMode;
        MotorRegulationMode(byte MotorRegulationMode){this.MotorRegulationMode = MotorRegulationMode; }
    }

    /// <summary>
    /// Enumeration of motor run states.
    /// </summary>
    /// 
    public enum MotorRunState
    {
        /// <summary>
        /// Motor will be idle.
        /// </summary>
        Idle((byte)0x00),
        /// <summary>
        /// Motor will ramp-up.
        /// </summary>
        RampUp((byte)0x10),
        /// <summary>
        /// Motor will be running.
        /// </summary>
        Running((byte)0x20),
        /// <summary>
        /// Motor will ramp-down.
        /// </summary>
        RampDown((byte)0x40);
        byte MotorRunState;
        MotorRunState(byte MotorRunState){this.MotorRunState = MotorRunState;}
    }
    public enum NXTCommandType
    {
        /// <summary>
        /// Direct command, which requires reply.
        /// </summary>
        DirectCommand((byte)0x00),

        /// <summary>
        /// System command, which requires reply.
        /// </summary>
        SystemCommand((byte)0x01),

        /// <summary>
        /// Reply command received from NXT brick.
        /// </summary>
        ReplyCommand((byte)0x02),

        /// <summary>
        /// Direct command, which does not require reply.
        /// </summary>
        DirectCommandWithoutReply((byte)0x80),

        /// <summary>
        /// System command, which does not require reply.
        /// </summary>
        SystemCommandWithoutReply((byte)0x81);
        public final byte NXTCommandType;
        NXTCommandType(byte NXTCommandType){this.NXTCommandType = NXTCommandType;}
    }

    /// <summary>
    /// Enumeration of system commands supported by Lego Mindstorms NXT brick.
    /// </summary>
    /// 
    public enum NXTSystemCommand
    {
        /// <summary>
        /// Get firmware version of NXT brick.
        /// </summary>
        GetFirmwareVersion((byte)0x88),

        /// <summary>
        /// Set NXT brick name.
        /// </summary>
        SetBrickName((byte)0x98),

        /// <summary>
        /// Get device information.
        /// </summary>
        GetDeviceInfo((byte)0x9B);
        public final byte NXTSystemCommand;
        NXTSystemCommand(byte NXTSystemCommand){this.NXTSystemCommand = NXTSystemCommand;}
    }

    /// <summary>
    /// Enumeration of direct commands supported by Lego Mindstorms NXT brick.
    /// </summary>
    /// 
    public enum NXTDirectCommand
    {
        /// <summary>
        /// Keep NXT brick alive.
        /// </summary>
        KeepAlive((byte)0x0D),
        
        /// <summary>
        /// Start a progam.
        /// </summary>
        Program((byte)0x00),

        /// <summary>
        /// open file write handler.
        /// </summary>
        OpenWrite((byte)0x81),
        
        /// <summary>
        /// write data to handler
        /// </summary>
        ReadCommand((byte)0x82),

        /// <summary>
        /// write data to handler
        /// </summary>
        WriteCommand((byte)0x83),

        /// <summary>
        /// close handler
        /// </summary>
        CloseHandler((byte)0x84),

        /// <summary>
        /// Delete handler
        /// </summary>
        DeleteCommand((byte)0x85),

        /// <summary>
        /// Stop any progam.
        /// </summary>
        StopProgram((byte)0x00),
        
        /// <summary>
        /// Play tone of specified frequency.
        /// </summary>
        PlayTone((byte)0x03),

        /// <summary>
        /// Get battery level.
        /// </summary>
        GetBatteryLevel((byte)0x0B),

        /// <summary>
        /// Set output state.
        /// </summary>
        SetOutputState((byte)0x04),

        /// <summary>
        /// Get output state.
        /// </summary>
        GetOutputState((byte)0x06),

        /// <summary>
        /// Reset motor position.
        /// </summary>
        ResetMotorPosition((byte)0x0A),

        /// <summary>
        /// Set input mode.
        /// </summary>
        SetInputMode((byte)0x05),

        /// <summary>
        /// Get input values.
        /// </summary>
        GetInputValues((byte)0x07),

        /// <summary>
        /// Reset input scaled value.
        /// </summary>
        ResetInputScaledValue((byte)0x08);
        public final byte NXTDirectCommand;
        NXTDirectCommand(byte NXTDirectCommand){this.NXTDirectCommand = NXTDirectCommand;}
        
        
    }
    /// <summary>
    /// Structure, which describes motor's state.
    /// </summary>
    /// 
    public class MotorState
    {
        /// <summary>
        /// Power, [-100, 100].
        /// </summary>
        public int Power;
        /// <summary>
        /// Turn ratio, [-100, 100].
        /// </summary>
        public int TurnRatio;
        /// <summary>
        /// Mode (bit field).
        /// </summary>
        public MotorMode Mode;
        /// <summary>
        /// Regulation mode.
        /// </summary>
        public MotorRegulationMode Regulation;
        /// <summary>
        /// Run state.
        /// </summary>
        public MotorRunState RunState;
        /// <summary>
        /// Tacho limit (0 - run forever).
        /// </summary>
        /// 
        /// <remarks>The value determines motor's run limit.</remarks>
        public int TachoLimit;
        /// <summary>
        /// Number of counts since last reset of motor counter.
        /// </summary>
        /// 
        /// <remarks><note>The value is ignored when motor's state is set. The value is
        /// provided when motor's state is retrieved.</note></remarks>
        public int TachoCount;
        /// <summary>
        /// Current position relative to last programmed movement.
        /// </summary>
        /// 
        /// <remarks><note>The value is ignored when motor's state is set. The value is
        /// provided when motor's state is retrieved.</note></remarks>
        public int BlockTachoCount;
        /// <summary>
        /// Current position relative to last reset of motor's rotation sensor.
        /// </summary>
        /// 
        /// <remarks><note>The value is ignored when motor's state is set. The value is
        /// provided when motor's state is retrieved.</note></remarks>
        public int RotationCount;
    }

    

    // communication interfaced used for communication with NXT brick
    public Port communicationInterface;
    //process of download/uploading
    public float process = 0;
    /// <summary>
    /// Check if connection to NXT brick is established.
    /// </summary>
    /// 
    public Boolean IsConnected;
    
   public Boolean getIsConnected()
        {
            
                return ( communicationInterface != null );
            
        }
    

    /// <summary>
    /// Initializes a new instance of the <see cref="NXTBrick"/> class.
    /// </summary>
    /// 
    public NxtControl( )
    {
    }

    /// <summary>
    /// Destroys the instance of the <see cref="NXTBrick"/> class.
    /// </summary>
    /// 
    ////TODO: check this
    public void destroy() {
    	Disconnect( );
      }
        
	

    /// <summary>
    /// Connect to NXT brick.
    /// </summary>
    /// 
    /// <param name="portName">Serial port name to use for communication, for example COM1.</param>
    /// 
    /// <returns>Returns <b>true</b> on successful connection or <b>false</b>
    /// otherwise.</returns>
    /// 
    /// <remarks>If connection to NXT brick was established before the call, existing connection will be reused.
    /// If it is required to force reconnection, then <see cref="Disconnect"/> method should be called before.
    /// </remarks>
    /// 
    public Boolean Connect( String portName )
    {
        
            if ( communicationInterface != null )
                return true;

            // create communication interface,
            communicationInterface = new Port( );
           
            // connect and check if NXT is alive
            try {
				if ( ( communicationInterface.connect(portName ) )  )
				    return true;
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}

            Disconnect( );
        
        return false;
    }

    /// <summary>
    /// Disconnect from Lego NXT brick.
    /// </summary>
    /// 
    public void Disconnect( )
    {
        
            if ( communicationInterface != null )
            {
                communicationInterface.Disconnect( );
                communicationInterface = null;
            }
        
    }
    /**
     * Plays a tune on Nxt
     * @param frequency Frequency in Hz
     * @param duration Duration in ms
     * @return Return result, true or false;
     */
    public boolean PlayTone(short frequency, short duration)
    {
        byte[] command = new byte[6];

        // prepare command
        command[0] = (byte)NXTCommandType.DirectCommand.NXTCommandType;
        command[1] = (byte)NXTDirectCommand.PlayTone.NXTDirectCommand;
        command[2] = (byte)(frequency & 0xFF);
        command[3] = (byte)(frequency >> 8);
        command[4] = (byte)(duration & 0xFF);
        command[5] = (byte)(duration >> 8);

        // execute command
        if(SendCommand(command,6) == null){
        	return false;
        }
        return true;
    }
    /**
     * This function will send the command to communicationInterface
     * @param command
     * @return Return byte[] reply or null if failed
     */
    public byte[] SendCommand( byte[] command, int length)
    {
       byte[] reply=null;
            // check connection
            if ( communicationInterface == null )
            {
                System.out.print( "Not connected to NXT brick" );
                return null;
            }

            // send message to NXT brick
            communicationInterface.SendMessage( new byte[]{(byte)length,(byte)0}, 2 );
            communicationInterface.SendMessage( command, length );
            if (true )
            {
                
            	try {
					Thread.sleep(200);
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
                // read message
                reply = communicationInterface.ReadMessage(  );
                if ( reply != null )
                {
                    // check that reply corresponds to command
                    if (reply[1] != command[1])
                    {
                    	System.out.print("Reply does not correspond to command");
                        return null;
                    }

                    // check for errors
                    if ( reply[2] != 0 )
                    {
                    	System.out.print("Error occured in NXT brick. Error code: " + reply[2]);
                        return null;
                    }

                    
                }
            }
        

        return reply;
    }
    /**
     * This function will translate a string in a function
     * @param command Command string
     * @return Result of action: true or false;
     */
    public boolean CommandReader(String command){
    	String[] command_parts;
    	command_parts = command.split(" ");
    	if((command_parts[0]).equals("cmd")){
    		if((command_parts[1]).equals("tune")){
    			return PlayTone(Short.parseShort(command_parts[2]),Short.parseShort(command_parts[3]));
    		}
    		return true;
    	}else{
    	return false;
    	}
    }
    
   
}
   

	

